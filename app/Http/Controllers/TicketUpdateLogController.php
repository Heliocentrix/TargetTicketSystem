<?php

namespace TargetInk\Http\Controllers;

use Illuminate\Http\Request;

use TargetInk\Http\Requests;
use TargetInk\Http\Controllers\Controller;
use TargetInk\TicketUpdateLog;
use Excel;

class TicketUpdateLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ticketlog = view('dashboard.tickets_logs.ticketlogView');
        if(request()->ajax()) {
            return $ticketlog;
        } else {
            return view('dashboard', compact('ticketlog'));
        }
    }

    public function exportTicketLogs(Request $request)
    {
        //To Read Pound Sign
        echo "\xEF\xBB\xBF";

        Excel::create('TIMS Ticket Update Cost Report', function($excel) use ($request) {
            $excel->setTitle('Ticket Cost Update Log');
            $excel->sheet('Ticket Cost Logs', function($sheet) use ($request) {
                $sheet->with($this->generateTicketLogs($request));
            });
        })->download('csv',array('Content-Encoding'=> 'UTF-8'));

    }

    private function generateTicketLogs(Request $request)
    {
        if(!is_string($request->from) || !is_string($request->to))
        {
            abort(404);
        }

        $tickets_log = TicketUpdateLog::with(['tickets','users'])
                                        ->where('ticket_update_log.created_at', '>=', \Carbon\Carbon::parse($request->from)->format('Y-m-d H:i:s'))
                                        ->where('ticket_update_log.created_at', '<=', \Carbon\Carbon::parse($request->to)->format('Y-m-d H:i:s'))
                                        ->get();

        return $this->formatTicketLogs($tickets_log);

    }

    private function formatTicketLogs($rows)
    {
        $result = [];

        foreach($rows as $ticket)
        {
           $ticketRep = [];

           $ticketRep['Ticket ref. no'] = $ticket->ticket_id;
           $ticketRep['Ticket Title'] = $ticket->tickets->title;
           $ticketRep['Ticket Cost'] = '£ '.$ticket->cost;
           $ticketRep['Date updated'] = $ticket->created_at;
           $ticketRep['User Email'] = $ticket->users->email;
           $ticketRep['User Name'] = $ticket->users->name;

           $result[] = $ticketRep;

        }

        return $result;

    }
}
