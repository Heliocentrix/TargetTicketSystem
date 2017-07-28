<?php

namespace TargetInk\Http\Controllers;

use Illuminate\Http\Request;
use Image;
use Mail;
use Storage;
use TargetInk\Attachment;
use TargetInk\Http\Controllers\Controller;
use TargetInk\Http\Middleware\OwnCompany;
use TargetInk\Http\Requests;
use TargetInk\Http\Requests\ResponseRequest;
use TargetInk\Http\Requests\TicketRequest;
use TargetInk\Response;
use TargetInk\Ticket;
use TargetInk\TicketUpdateLog;
use TargetInk\User;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $company_slug)
    {
        $this->middleware('ownCompany');

        if($request->has('archived')) {
            $archived = 1;
        } else {
            $archived = 0;
        }

        $client = User::where('company_slug', $company_slug)->with(['tickets' => function ($q) use ($request, $archived) {
            $q->where('archived', '=', $archived);
        }, 'tickets.client', 'tickets.responses'])->first();

        return view('tickets.ticketList', compact('archived', 'client'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company_slug)
    {
        $this->middleware('ownCompany');
        return view('tickets.ticketEdit', compact('company_slug'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($company_slug, TicketRequest $request)
    {
        $this->middleware('ownCompany');
        $client = User::where('company_slug', $company_slug)->first();

        if($request->published_at) {
            $published_at_date = explode('/', $request->published_at);
            if(is_array($published_at_date) && isset($published_at_date[0]) && isset($published_at_date[1]) && isset($published_at_date[2])) {
                $published_at_date = $published_at_date[2]. '-' . $published_at_date[1]. '-' . $published_at_date[0];
            } else {
                $published_at_date = '0000-00-00';
            }
        } else {
            $published_at_date = '0000-00-00';
        }

        $ticket = new Ticket;
        $ticket->fill($request->all());
        $ticket->client_id = $client->id;
       
        $order = Ticket::where('client_id', '=', $client->id)->where('archived', '=', 0)->orderBy('order', 'desc')->first();
        
        if($order) {
            $order = $order->order +1;
        } else {
            $order = 1;
        }
        
        $ticket->order = $order;
        $ticket->save();

        $response = new Response;
        $response->fill($request->all());
        $response->ticket_id = $ticket->id;
        $response->admin = \Auth::user()->admin;
        $response->published_at = $published_at_date;
        $response->save();

        self::processFileUpload($request, $response->id);

        // Send an email
        $recipients = [
            $client->email => $client->instant,
            config('app.email_to') => false,
        ];

        if($client->second_email) {
            $recipients[$client->second_email] = $client->instant;
        }

        $this->emailTicketSend($recipients, $client, $response, $ticket, 'new');

        return redirect('/')->with('ticket_success', true)->with('company_slug', $company_slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $company_slug
     * @return \Illuminate\Http\Response
     */
    public function show($company_slug, $ticket_id)
    {
        $this->middleware('ownCompany');
        $ticket = Ticket::with('responses')->with('responses.attachments')->findOrFail($ticket_id);
        if($ticket->client->company_slug != $company_slug) {
            return redirect('/');
        }

        return view('tickets.ticketShow', compact('ticket', 'company_slug'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $company_slug, $id)
    {
        $this->middleware('ownCompany');
        $ticket = Ticket::find($id);
        
        if($request->has('cost') && $request->input('cost') != $ticket->cost){
            $ticket_log = new TicketUpdateLog;
            $ticket_log->ticket_id = $ticket->id;
            $ticket_log->cost = (float)$request->input('cost');
            $ticket_log->created_at = \Carbon\Carbon::now();
            $ticket_log->created_by = auth()->user()->id;
            $ticket_log->save();
        }

        if($ticket->client->company_slug != $company_slug) {
            return redirect('/');
        }


        $ticket->type = ($request->get('type'));
        $ticket->cost = ($request->get('cost'));

        $ticket->save();

        flash()->success('The ticket has been changed.');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($company_slug, $id)
    {
        $this->middleware('ownCompany');
        $ticket = Ticket::find($id);
        if($ticket->client->company_slug != $company_slug) {
            return redirect('/');
        }
        $ticket->delete();
        flash()->success('The ticket has been deleted.');
        return redirect()->back();
    }

    public function archive($company_slug, $ticket_id, $archive)
    {
        $this->middleware('ownCompany');
        $ticket = Ticket::find($ticket_id);
        if($ticket->client->company_slug != $company_slug) {
            return redirect('/');
        }
        $client_id = User::where('company_slug', $company_slug)->first()->id;
        $ticket->archived = $archive;
        $order = Ticket::where('client_id', '=', $client_id)->where('archived', '=', $archive)->orderBy('order', 'desc')->first();
        if($order) {
            $order = $order->order +1;
        } else {
            $order = 1;
        }
        $ticket->order = $order;
        $ticket->save();
        if($archive){
            flash()->success('The ticket has been successfully archived.');
        }else{
            flash()->success('The ticket has been successfully unarchived.');
        }
        return redirect()->back();
    }

    public function respond($company_slug, $ticket_id, $value)
    {
        $this->middleware('ownCompany');
        if(!\Auth::user()->admin){
            return redirect()->to('/');
        }

        $ticket = Ticket::find($ticket_id);
        if($ticket->client->company_slug != $company_slug) {
            return redirect('/');
        }
        $ticket->responded = $value;

        $ticket->save();
        if($value){
            flash()->success('The ticket has been marked as responded.');
        }else{
            flash()->success('The ticket has been marked as not responded.');
        }
        
        return redirect()->back();
    }

    /**
     * Set Order of the tickets
     * @param \Illuminate\Http\Request $request 
     */
    public function setOrder(Request $request)
    {
        $new_order = $request->input('new_order');
        
        if(is_array($new_order)){
            $totalTickets = count($new_order);
            foreach ($new_order as $key => $value) {
                Ticket::find($value)->update([
                    'order' => $totalTickets - $key
                ]);
            }
        }
        
    }

    public function move($direction, $user_id, $ticket_id, $archived)
    {
        $tickets = Ticket::where('client_id', $user_id)->where('archived', $archived)->orderBy('order', 'asc')->get();

        // Set initial order in case it has never been set
        $order = 0;
        foreach($tickets as $ticket) {
            $order++;
            $ticket->order = $order;
        }

        $previous = null;
        $current = null;
        foreach($tickets as $ticket) {
            if($direction == 'down') {
                if($previous) {
                    if($ticket_id == $ticket->id) {
                        // Swap the orders with the previous one
                        $tmp = $previous->order;
                        $previous->order = $ticket->order;
                        $ticket->order = $tmp;
                        unset($tmp);

                        $previous->save();
                        $ticket->save();
                    }
                }
            } elseif($direction == 'up') {
                if($current) {
                    // Current has been set, so swap it with the previous
                    $tmp = $ticket->order;
                    $ticket->order = $current->order;
                    $current->order = $tmp;
                    unset($tmp);

                    $current->save();
                    $ticket->save();

                    break;
                }
                
                if($ticket_id == $ticket->id) {
                    $current = $ticket;
                }
            } else {
                abort(500, 'Invalid direction');
            }

            $previous = $ticket;
        }

        return redirect()->back()->with('success', 'Successfully moved ticket');
    }

    public function addResponse($company_slug, $ticket_id, ResponseRequest $request)
    {
        $this->middleware('ownCompany');
        $response = new Response;
        $response->fill($request->all());
        if($request->has('working_time')) {
            $wt = explode(':', $request->get('working_time'));
            if(is_array($wt) && isset($wt[0]) && isset($wt[1])) {
                $response->working_time = 60 * $wt[0] + $wt[1];
            }
        }
        $response->admin = \Auth::user()->admin;
        $response->ticket_id = $ticket_id;
        $response->save();

        self::processFileUpload($request, $response->id);
        flash()->success('The response has been sent.');

        $client = User::where('company_slug', $company_slug)->first();
        $ticket = Ticket::find($response->ticket_id);

        // Update the responded flag
        $ticket->responded = \Auth::user()->admin;
        $ticket->save();

        // Send an email
        $recipients = [$client->email => $client->instant];
        if(!auth()->user()->admin) {
            $recipients[config('app.email_to')] = false;
        }

        if($client->second_email) {
            $recipients[$client->second_email] = $client->instant;
        }

        $this->emailTicketSend($recipients, $client, $response, $ticket, 'reply');

        return redirect()->back();
    }

    public function processFileUpload($request, $response_id)
    {
        // Loop through attachments
        foreach($request->all() as $request_key => $request_val) {
            if(substr($request_key, 0, 10) == 'attachment') {
                // We are uploading a file
                if($request->hasFile($request_key) && $request->file($request_key)->isValid()) {
                    $file = $request->file($request_key);
                    $extension = $file->getClientOriginalExtension();
                    $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $filename .= '_' . time() . '.' . $extension;

                    // Sanitize
                    $filename = mb_ereg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $filename);
                    $filename = mb_ereg_replace("([\.]{2,})", '', $filename);

                    if(in_array($extension, ['jpg', 'jpeg', 'gif', 'png'])) {
                        $doctype = 'I';
                    } else {
                        $doctype = 'D';
                    }

                    Storage::disk('s3')->put($filename, file_get_contents($request->file($request_key)->getRealPath()));

                    $attachment = new Attachment;
                    $attachment->type = $doctype;
                    $attachment->original_filename = $file->getClientOriginalName();
                    $attachment->filename = $filename;
                    $attachment->response_id = $response_id;
                    $attachment->save();
                }
            }
        }
    }

    public function editResponseTime(Request $request, $company_slug, $ticket_id, $response_id)
    {
        $ticket = Ticket::find($ticket_id);
        if($ticket->client->company_slug != $company_slug) {
            return redirect('/');
        }
        $response = Response::find($response_id);
        if($response->ticket_id != $ticket_id) {
            return redirect('/');
        }
        if($request->has('working_time')) {
            $wt = explode(':', $request->get('working_time'));
            $response->working_time = 60*$wt[0]+$wt[1];
        }
        $response->save();

        flash()->success('The response has been updated.');
        return redirect()->back();
    }

    private function emailTicketSend($recipients,$client, $response, $ticket, $type = null)
    {
        $ticketType = ['new' => 'newTicket', 'reply' => 'newTicketReply'];
        if(array_key_exists($type, $ticketType)){
            foreach($recipients as $recipientEmail => $recipientInstantKey) {
                Mail::send('emails.' . $ticketType[$type], ['instant' => $recipientInstantKey, 'user' => $client, 'response' => $response, 'ticket' => $ticket], function ($message) use ($client, $response, $ticket, $recipientEmail) {
                    $message->to($recipientEmail);

                    $priority = '';
                    if($ticket->priority) {
                        $priority = 'PRIORITY ';
                    }

                    $message->subject($priority . 'Support Request:' . $ticket->getRef());
                });
            }
        }
        
    }


    public function editResponse($companySlug, Ticket $ticket, Response $response)
    {
        return view('tickets.response.edit', compact('response', 'companySlug', 'ticket'));
    }

    public function updateResponse($companySlug, Ticket $ticket, Response $response)
    {
        $response->update([
            'content' => request('content')
            ]);
        flash()->success('The response has been updated.');
        return redirect("{$companySlug}/tickets/{$ticket->id}");
    }
}
