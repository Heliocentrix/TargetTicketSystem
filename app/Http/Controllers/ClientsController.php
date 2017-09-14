<?php

namespace TargetInk\Http\Controllers;

use Illuminate\Http\Request;
use TargetInk\Http\Requests;
use TargetInk\Http\Requests\ClientCreateRequest;
use TargetInk\Http\Requests\ClientUpdateRequest;
use TargetInk\Http\Controllers\Controller;

use TargetInk\User;
use TargetInk\Libraries\Slug;
use Mail;
use Storage;

class ClientsController extends Controller
{
    // Should be admin
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $clients = User::where('admin', 0)->orderBy('company')->get();

        $clientList = view('dashboard.clients.clientList', compact('clients'));
        if($request->ajax()) {
            return $clientList;
        } else {
            return view('dashboard', compact('clientList'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $client_id = 0;
        $clientForm = view('dashboard.clients.clientEdit', compact('client_id'));
        if($request->ajax()) {
            return $clientForm;
        } else {
            $clients = User::where('admin', 0)->orderBy('company')->get();
            $clientList = view('dashboard.clients.clientList', compact('clients', 'clientForm'));
            return view('dashboard', compact('clientList'));
        }  
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientCreateRequest $request)
    {
        $client = new User;
        $client->fill($request->except(['password']));
        $client->password = bcrypt($request->get('password'));
        $client->company_slug = Slug::make($request->get('company'), 'users', 'company_slug');
        $client->instant = sha1($request->get('name') . $request->get('email') . $request->get('company'));

        if($request->hasFile('company_logo') && $request->file('company_logo')->isValid()) {
            $file = $request->file('company_logo');
            $extension = $file->getClientOriginalExtension();
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $counter = 2;
            $fcache = $filename;
            while(Storage::disk('s3')->has($filename . '.' . $extension)) {
                $filename = $fcache . '_' . $counter;
                $counter++;
            }

            $filename = $filename . '.' . $extension;

            // Sanitize
            $filename = mb_ereg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $filename);
            $filename = mb_ereg_replace("([\.]{2,})", '', $filename);

            Storage::disk('s3')->put($filename, file_get_contents($request->file('company_logo')->getRealPath()));

            $client->company_logo = $filename;
        }

        // Save the client
        $client->save();
        $client->pre_pass = $request->get('password');

        // Send an email
        $recipients = [
            $client->email => $client->instant,
            config('app.email_to') => $client->instant,
        ];

        if($client->second_email) {
            $recipients[$client->second_email] = $client->instant;
        }

        foreach($recipients as $recipientEmail => $recipientInstantKey) {
            Mail::send('emails.newUser', ['instant' => $recipientInstantKey, 'user' => $client], function ($message) use ($client, $recipientEmail) {
                $message->to($recipientEmail);
                $message->subject('Maintenance Account setup for ' . $client->email);
            });
        }

        return redirect()->back()->with('success', 'The client has been created')->with('client', $client);
    }

    /**
     * Redirect to the edit page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return redirect()->route('clients.edit', ['id' => $id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $client = User::where('admin', 0)->findOrFail($id);
        $client->password = null;
        $client_id = $client->id;
        $clientForm = view('dashboard.clients.clientEdit', compact('client', 'client_id'));
        if($request->ajax()) {
            return $clientForm;
        } else {
            $clients = User::where('admin', 0)->orderBy('company')->get();
            $clientList = view('dashboard.clients.clientList', compact('clients', 'clientForm'));
            return view('dashboard', compact('clientList'));
        } 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ClientUpdateRequest $request, $id)
    {
        $client = User::find($id);
        $client->fill($request->except(['password']));

        if($request->has('password')) {
           $client->password = bcrypt($request->get('password'));
        }

        if($request->hasFile('company_logo') && $request->file('company_logo')->isValid()) {
            $file = $request->file('company_logo');
            $extension = $file->getClientOriginalExtension();
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $counter = 2;
            $fcache = $filename;
            while(Storage::disk('s3')->has($filename . '.' . $extension)) {
                $filename = $fcache . '_' . $counter;
                $counter++;
            }

            $filename = $filename . '.' . $extension;

            // Sanitize
            $filename = mb_ereg_replace("([^\w\s\d\-_~,;:\[\]\(\).])", '', $filename);
            $filename = mb_ereg_replace("([\.]{2,})", '', $filename);

            Storage::disk('s3')->put($filename, file_get_contents($request->file('company_logo')->getRealPath()));

            $client->company_logo = $filename;
        }

        // Save the client
        $client->save();

        return redirect()->back()->with('success', 'The client has been updated')->with('client', $client);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = User::find($id);
        $client->email = $client->email . '_' . $id . '_' . md5($id);
        $client->save();
        $client->delete();

        return json_encode([
            'success'   => 'The Client has been deleted.',
            'method'    => 'delete',
            'id'        => $client->id,
        ]);
    }
}
