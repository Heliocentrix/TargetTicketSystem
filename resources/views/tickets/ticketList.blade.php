@extends('includes.layout')

@section('sectionTitle')
    @if(auth()->user()->admin)
        Client Tickets
    @else
        Your tickets
    @endif
@stop

@section('content')
<script>
    @if($archived)
    var $archived = true;
    @else
    var $archived = false;
    @endif

    var $client_id = {{ $client->id }};
</script>

@if(!auth()->user()->admin)
<div class="page-heading text-center">
    <a href="{{ url($client->company_slug . '/tickets/create') }}" class="btn btn-info target-btn btn-new-ticket">CREATE A NEW TICKET</a>
</div>
@else
<div class="page-heading text-center">
    <h1>Client Tickets</h1>
    <p>Choose a ticket to reply, update, archive or delete</p>
    <a href="{{ url($client->company_slug . '/tickets/create') }}" class="btn btn-info target-btn btn-new-ticket">START NEW TICKET</a>
</div>
@endif

@include('flash::message')

<div class="page-content row">
    <div class="col-xs-12">
        <div class="border-b ticket-pad">
            <a href="{{ url($client->company_slug . '/tickets') }}" class="btn-open-tickets @if(!$archived) active @endif "><i></i> View Open Tickets</a>
            <a href="{{ url($client->company_slug . '/tickets?archived=1') }}" class="btn-archived-tickets @if($archived) active @endif "><i></i> View Archived Tickets</a>
        </div>
    </div>
    <div class="col-xs-12">
        <table class="table text-muted table-muted table-no-borders no-margin">
            <tr>
                <td class="click-only goto-column">
                    <i class="icon-goto"></i>
                </td>
                <td class="td-adjust click-only hidden-xs">
                    Click on the arrow to view ticket
                </td>
                <td class="td-adjust text-right click-only">
                    Click, hold &amp; drag icon to Reprioritise
                </td>
                <td class="td-adjust touch-only hidden">
                    Tap to View Ticket
                </td>
                <td class="td-adjust text-right touch-only hidden">
                    Tap to Reprioritise Ticket
                </td>
                <td width="36" class="hidden-xs click-only">
                    <i class="icon-move icon-muted"></i>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-xs-12">
        @if(count($client->tickets))
            <table class="table ticket-table sorted_table" id="ticket_table">
                <thead>
                    <tr>
                        <th class="click-only goto-column"></th>
                        <th>Ticket Title</th>
                        <th class="hidden-sm hidden-xs">Ref No.</th>
                        <th class="hidden-xs">Ticket Type</th>
                        <th class="hidden-xs">Priority</th>
                        <th class="hidden-xs">Cost</th>
                        <th class="text-center">Response</th>
                        @if($archived)
                        <th class="text-center hidden-xs">Unarchive</th>
                        @else
                        <th class="text-center hidden-xs">Archive</th>
                        @endif
                        @if(auth()->user()->admin)
                            <th class="hidden-xs">Time</th>
                            <th class="hidden-xs"></th>
                        @endif
                        <th width="36"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($client->tickets as $ticket)
                        <tr id="{{ $ticket->id }}" class="tappable-row" data-url="{{ $ticket->getUrl() }}">
                            <td class="click-only"><a href="{{ $ticket->getUrl() }}" class="show-on-hover icon-goto" name="goto-ticket"></a></td>
                            <td class="td-adjust">{{ $ticket->title }}</td>
                            <td class="td-adjust hidden-sm hidden-xs">{{ $ticket->getRef() }}</td>
                            <td class="td-adjust hidden-xs">{{ $ticket->getType() }}</td>
                            <td class="td-adjust hidden-xs">{{ $ticket->getPriority() }}</td>
                            <td class="td-adjust hidden-xs">@if($ticket->cost) &pound;{{ $ticket->cost }} @else N/A @endif</td>
                            <td class="text-center">
                                @if(auth()->user()->admin)
                                    @if(@$ticket->responded)
                                        <a href="/{{$client->company_slug}}/tickets/{{$ticket->id}}/respond/0" class="icon-response" name="responded-ticket"></a>
                                    @else
                                        <a href="/{{$client->company_slug}}/tickets/{{$ticket->id}}/respond/1" class="icon-delete" name="not-responded-ticket"></a>
                                    @endif
                                @else
                                    @if(@$ticket->responded)
                                        <i class="icon-response"></i>
                                    @endif
                                @endif
                            </td>
                            @if($archived)
                            <td class="text-center hidden-xs">
                                <a href="/{{$client->company_slug}}/tickets/{{$ticket->id}}/archive/0" class="btn-unarchive" name="unarchive-ticket"></a>
                            </td>
                            @else
                            <td class="text-center hidden-xs">
                                <a href="/{{$client->company_slug}}/tickets/{{$ticket->id}}/archive/1" class="btn-archive" name="archive-ticket"></a>
                            </td>
                            @endif
                            @if(auth()->user()->admin)
                                <th class="td-adjust hidden-xs">{{$ticket->totalTime()}}</th>
                                <td class="td-adjust text-center hidden-xs">
                                    {!! Form::open(['url' => '/'.$client->company_slug.'/tickets/'.$ticket->id, 'method' => 'DELETE']) !!}
                                        {!! Form::submit('', ['class' => 'btn-delete icon-delete', 'name' => 'delete-ticket']) !!}
                                    {!! Form::close() !!}
                                </td>
                            @endif
                            <td class="click-only"><i class="show-on-hover icon-move"></i></td>
                            <td class="touch-only hidden">
                                <div>
                                    <a href="{{ url('api/move/ticket/up/' . $client->id . '/' . $ticket->id . '/' . $archived) }}" class="mobile-order-icon" @if($ticket == $client->tickets->first()) style="visibility:hidden" @endif ><i class="fa fa-2x fa-caret-up"></i></a>
                                </div>
                                <div>
                                    <a href="{{ url('api/move/ticket/down/' . $client->id . '/' . $ticket->id . '/' . $archived) }}" class="mobile-order-icon" @if($ticket == $client->tickets->last()) style="visibility:hidden" @endif><i class="fa fa-2x fa-caret-down"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-warning">There are no tickets to show</div>
        @endif
    </div>
</div>
@stop
