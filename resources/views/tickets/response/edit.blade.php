@extends('includes.layout')

@section('sectionTitle')
	@if(!isset($ticket))
		Create Ticket
	@else
		Edit Ticket
	@endif
@stop

@section('content')
<div class="page-heading text-center">
    <h1>Edit Response</h1>
</div>
<div class="page-content">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12">
			{!! Form::open(['route' => ['ticket.response.update', $companySlug,$ticket,$response], 'method' => 'PUT', 'class' => 'form-horizontal object-editor']) !!}
			<div class="ticket-form">
	           	<div @if($errors->has('content')) has-error dark @endif>
	           		@if($errors->has('content'))
	           			<span class="alert-warning"> {{ $errors->first('content') }} </span>
	           		@endif
					{!! Form::textarea('content', $response->content , ['placeholder' => 'Your Text', 'id' => 'content', 'class' => 'ticket-content']) !!}
		        </div>
			
			    <div>
			    	{!! Form::submit('submit', ['class' => 'btn btn-info btn-submit-ticket target-btn']) !!}
			    </div>
			</div>
		    {!! Form::close() !!}
		</div>
    </div>
</div>
@stop
