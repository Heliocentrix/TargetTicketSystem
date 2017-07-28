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
    <h1>Choose a ticket</h1>
    <p>Choose which kind of request to submit from the following:</p>
</div>
<div class="page-content">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1 col-sm-12">
			{!! Form::open(['url' => $company_slug.'/tickets', 'method' => 'POST', 'files' => true, 'class' => 'form-horizontal object-editor']) !!}
			<div class="text-center icon-container">
				{!! Form::radio('type', '1', !isset($ticket) || @$ticket->type == 1, ['class' => 'type', 'id' => 'web-amends']) !!} <label for="web-amends" class="btn-web-amends"></label>
				{!! Form::radio('type', '2', @$ticket->type == 2, ['class' => 'type', 'id' => 'add-content']) !!} <label for="add-content" class="btn-add-content"></label>
				{!! Form::radio('type', '3', @$ticket->type == 3, ['class' => 'type', 'id' => 'get-quote']) !!} <label for="get-quote" class="btn-get-quote"></label>
				{!! Form::radio('type', '4', @$ticket->type == 4, ['class' => 'type', 'id' => 'ask-question']) !!} <label for="ask-question" class="btn-ask-question"></label>
				{!! Form::radio('type', '5', @$ticket->type == 5, ['class' => 'type', 'id' => 'artwork']) !!} <label for="artwork" class="btn-artwork">ARTWORK</label>
			</div>
			<div class="ticket-form">
	           	<div @if($errors->has('title')) has-error dark @endif>
	           		@if($errors->has('title'))
	           			 <span class="alert-warning"> {{ $errors->first('title') }} </span>
	           		@endif
					{!! Form::text('title', @$ticket->title, ['class' => 'form-control form-title-input', 'placeholder' => 'Title']) !!}
		        </div>
		        <div id="scheduleDiv">
		        	{!! Form::text('schedule', @$ticket->schedule, ['class' => 'form-control', 'placeholder' => 'What\'s your schedule']) !!}
		        </div>
	           	<div @if($errors->has('content')) has-error dark @endif>
	           		@if($errors->has('content'))
	           			<span class="alert-warning"> {{ $errors->first('content') }} </span>
	           		@endif
					{!! Form::textarea('content', @$ticket->content, ['placeholder' => 'Your Text', 'id' => 'content', 'class' => 'ticket-content']) !!}
		        </div>
				<div class="col-xs-12">
			        @include('includes.fileInput')
				</div>
		        <div id="publishedAtDiv">
		        	<div>
		        		@if(@$ticket->published_at)
		        			{!! Form::text('published_at', date('d m Y', strtotime(@$ticket->published_at)), ['class' => 'form-control', 'placeholder' => 'Date To Be Published (Please use format: DD/MM/YYYY)']) !!}
		        		@else
		        			{!! Form::text('published_at', '', ['class' => 'form-control dateInput', 'placeholder' => 'Date To Be Published (Please use format: DD/MM/YYYY)']) !!}
		        		@endif
		        	</div>
		        </div>
		        <div id="authorDiv">
		        	<div>
		        		{!! Form::text('author', @$ticket->author, ['class' => 'form-control', 'placeholder' => 'Author, Google Plus Account if required']) !!}
		        	</div>
		        </div>
		        <div id="categoriesDiv">
		        	<div>
		        		{!! Form::text('categories', @$ticket->categories, ['class' => 'form-control', 'placeholder' => 'Categories']) !!}
		        	</div>
		        </div>
		        <div id="artitcleTitleDiv">
		        	<div>
		        		{!! Form::text('article_title', @$ticket->article_title, ['class' => 'form-control', 'placeholder' => 'Article Or Event Title']) !!}
		        	</div>
		        </div>
			    <div>
			    	{!! Form::submit('submit', ['class' => 'btn btn-info btn-submit-ticket target-btn']) !!}
			    </div>

				<div class="ticket-check">
				    {!! Form::checkbox('priority', '1', @$ticket->priority) !!}
					<label for="priority">Priority Request? All priority request will be invoiced!<br>Clients with a maintenance contract will only be invoiced should the time exceed your monthly allowance. PLEASE NOTE you will not be informed if the time runs over your allowance and you will be charged by the hour until the job is completed. If you have any questions or querties please get in touch on 01892 800 400</label>
				</div>
			</div>
		    {!! Form::close() !!}
		</div>
    </div>
</div>
@stop
