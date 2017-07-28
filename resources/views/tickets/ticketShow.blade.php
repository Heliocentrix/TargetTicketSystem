@extends('includes.layout')

@section('sectionTitle')
	@if(auth()->user()->admin)
		Admin Response
	@else
		Open Ticket
	@endif
@stop

@section('content')
<div class="page-heading text-center">
    <h1>Ticket Details</h1>
</div>

<div class="page-content">
    <div class="row">
        <div class="col-md-10 col-md-offset-1 form-horizontal">
        	@include('flash::message')

			<div class="form-group">
				{!! Form::label('title', 'Ticket Title', ['class' => 'col-sm-4 form-label']) !!}
				<div class="col-sm-8">
					{!! Form::text('title', $ticket->title, ['class' => 'form-control', 'disabled']) !!}
				</div>
			</div>

            <div class="form-group">
           		{!! Form::label('ref_no', 'Reference No.', ['class' => 'col-sm-4 form-label']) !!}
           		<div class="col-sm-8">
           			{!! Form::text('ref_no', $ticket->id, ['class' => 'form-control', 'disabled']) !!}
           		</div>
            </div>

			@if(auth()->user()->admin)
           		{!! Form::model($ticket, ['url' => '/' . $company_slug . '/tickets/' . $ticket->id, 'method' => 'PUT']) !!}
           	@endif

            <div class="form-group">
           		{!! Form::label('type', 'Ticket Type', ['class' => 'col-sm-4 form-label']) !!}
           		<div class="col-sm-8">
           			@if(auth()->user()->admin)
           				{!! Form::select('type', [1 => "Web Amends", 2 => "Add Content", 3 => "Get Quote", 4 => "Ask Question"], $ticket->type, ['class' => 'form-control']) !!}
           			@else
	           			{!! Form::select('type', [1 => "Web Amends", 2 => "Add Content", 3 => "Get Quote", 4 => "Ask Question"], $ticket->type, ['class' => 'form-control', 'disabled']) !!}
	           		@endif
           		</div>
            </div>

            <div class="form-group">
           		{!! Form::label('total_working_time', 'Time', ['class' => 'col-sm-4 form-label']) !!}
           		<div class="col-sm-8">
           			{!! Form::text('total_working_time', $ticket->totalTime(), ['class' => 'form-control', 'disabled']) !!}
           		</div>
            </div>

            @if(auth()->user()->admin)
          		<div class="form-group">
	           		{!! Form::label('cost', 'Cost (&pound;)', ['class' => 'col-sm-4 form-label']) !!}
	           		<div class="col-sm-8">
	           			{!! Form::text('cost', $ticket->cost, ['class' => 'form-control']) !!}
	           		</div>
	            </div>
           	@elseif($ticket->cost)
           		<div class="form-group">
	           		{!! Form::label('cost', 'Cost (&pound;)', ['class' => 'col-sm-4 form-label']) !!}
	           		<div class="col-sm-8">
	           			{!! Form::text('cost', $ticket->cost, ['class' => 'form-control', 'disabled']) !!}
	           		</div>
	            </div>
           	@endif

            @if(auth()->user()->admin)
           		<div class="form-group">
           			<div class="col-xs-12">
		           		{!! Form::submit('Update Ticket', ['class' => 'btn btn-primary target-btn big-btn pull-right']) !!}
	            		{!! Form::close() !!}
            		</div>
	            </div>
            @endif
		</div>
		<div class="col-md-12">
			<hr>
		</div>
		<div class="col-md-10 col-md-offset-1">
			@if(count($ticket->responses))
	            @foreach($ticket->responses as $response)
	            	<div class="panel panel-default @if($response->admin) admin_response @else client_response @endif">
						<div class="panel-heading text-center">
							<span>@if($response->admin) Support: Response @else Client: Response @endif</span>
							<br class="visible-xs">
							<span>Date: {{ date('d/m/y', strtotime($response->created_at)) }}</span>
							<br class="visible-xs">
							<span>Time: {{ date('H:i', strtotime($response->created_at)) }}</span>
							@if($response->admin)
								@if(auth()->user()->admin)
									{!! Form::open(['url' => $company_slug . '/tickets/' . $ticket->id . '/' . $response->id . '/edittime', 'method' => 'POST', 'class' => 'form-horizontal hidden object-editor']) !!}
											{!! Form::text('working_time', $response->formatWorkingTime(), ['class' => 'hourInput']) !!}
										{!! Form::submit('save') !!}
									{!! Form::close() !!}
								@else
									({{ $response->formatWorkingTime() }})
								@endif
							@endif

							@if($response->admin)
								<a class="btn btn-primary btn-xs pull-right" 
									style="min-width: initial;" 
									href="{{ url($company_slug . '/tickets/' . $ticket->id . '/' . $response->id . '/editResponse') }}">

									<i class="fa fa-pencil" aria-hidden="true"></i>
								</a>
							@endif
						</div>
						<div class="panel-body">
							<div>{!! nl2br($response->content, false) !!}</div>

							@if($response->article_title)
								<div class="response_data">Article Title: {{ $response->article_title }}</div>
							@endif
							@if($response->categories)
								<div class="response_data">Categories: {{ $response->categories }}</div>
							@endif
							@if($response->published_at != '0000-00-00')
								<div class="response_data">Published At: {{ date('d/m/y', strtotime($response->published_at)) }}</div>
							@endif
							@if($response->author)
								<div class="response_data">Author: {{ $response->author }}</div>
							@endif
							@if($response->schedule)
								<div class="response_data">Schedule: {{ $response->schedule }}</div>
							@endif

							@foreach($response->attachments as $attachment)
							<div class="attachment">
								@if($attachment->type == 'I')
									<img src="/img/{{ $attachment->filename }}?w=510&amp;fit=max" alt="{{ $attachment->original_filename }}" class="img-responsive">
								@else
									<p class="document">{{ $attachment->getFilename() }}</p>
								@endif
								<a href="{{ config('app.asset_url') . $attachment->filename }}" target="_blank" class="icon-download"></a>
							</div>
							@endforeach
						</div>
	            	</div>
	            @endforeach
			@else
				<div class="alert alert-warning">There are no responses yet</div>
			@endif

            {!! Form::open(['url' => $company_slug . '/tickets/' . $ticket->id . '/addresponse', 'method' => 'POST', 'files' => true, 'class' => 'object-editor']) !!}

            	<div class="form-group @if($errors->has('content')) has-error dark @endif ">
	           		@if($errors->has('content'))
	           			<span class="alert-warning"> {{ $errors->first('content') }} </span>
	           		@endif
					{!! Form::textarea('content', '', ['class' => 'form-control', 'placeholder' => 'Add to ticket...']) !!}
		        </div>

				@include('includes.fileInput')

		    	@if(auth()->user()->admin)
				<div class="form-group">
		    		{!! Form::text('working_time', '', ['placeholder' => '00:00', 'class' => 'hourInput form-control']) !!}
				</div>
		    	@endif

				<div class="text-center">
					@if(auth()->user()->admin)
				    {!! Form::submit('Respond', ['class' => 'btn btn-success btn-ticket-respond target-btn target-btn-success']) !!}
				    @else
				    {!! Form::submit('Respond', ['class' => 'btn btn-info btn-ticket-respond target-btn target-btn-info']) !!}
				    @endif			    
				</div>
            {!! Form::close() !!}
		</div>
    </div>
</div>

@stop
