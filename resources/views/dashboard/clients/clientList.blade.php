<h2 class="maintenance-title pull-left">Clients</h2> <a href="#" class="clientFormToggler icon-new-client pull-left" clientId="0"></a>

@if(session('success'))
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

<table class="table ticket-table" id="client-table">
    <thead>
        <th class="goto-column hidden-xs"></th>
        <th>Name</th>
        <th class="hidden-xs">Company</th>
        <th class="hidden-sm hidden-xs">Last Login</th>
        <th class="hidden-sm hidden-xs">Active</th>
        <th class="text-center">Delete</th>
    </thead>
    <tbody>
        @foreach($clients as $client)
            <tr id="client-row-{{ $client->id }}" class="" clientId="{{ $client->id }}">
                <td class="hidden-xs"><a href="#" class="show-on-hover icon-goto" clientId="{{ $client->id }}"></a></td>
                <td class="td-adjust clientFormToggler" clientId="{{ $client->id }}" id="client-name-{{ $client->id }}">{{ $client->name }}</td>
                <td class="td-adjust hidden-xs clientFormToggler" clientId="{{ $client->id }}" id="client-company-{{ $client->id }}">{{ $client->company }}</td>
                <td class="td-adjust hidden-sm hidden-xs"></td>
                <td class="td-adjust">
                   {!! Form::open(['route' => ['clients.active', $client->id], 'id' => 'client-active', 'class' => 'form-horizontal', 'method' => 'PUT']) !!}
                    <input type="hidden" name="active" value="{{ !$client->active }}">
                    <button type="submit" class="btn {{ $client->active ? 'btn-success' : 'btn-danger'}}" style="min-width: 50px;"> 
                        <i class="fa {{ $client->active ? 'fa-check' : 'fa-times'}} " aria-hidden="true"></i>
                    </button>
                   {!! Form::close() !!} 
                </td>
                <td>
                    <a href="/clients/delete" class="ajax-delete icon-delete" data-type="client" data-contentid="{{ $client->id }}" data-uri="/clients/" data-delrow="#client-row-{{ $client->id }}"></a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div id="clientFormDiv" class="row">
    @if(request()->segment(1) == 'clients' && (request()->segment(2) == 'create' || request()->segment(3) == 'edit'))
    {!! $clientForm !!}
    @endif
</div>
