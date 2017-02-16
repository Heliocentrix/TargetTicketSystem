<div class="col-md-6 col-md-offset-3">
    {!! Form::open(['url' => 'wew']) !!}
        {{-- <div class="form-group">
             <div class="input-group">
                {!! Form::text('from','',['class' => 'form-control rticket-date','placeholder'=>'Date From']) !!}
                <div class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></div>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                {!! Form::text('to','',['class' => 'form-control rticket-date','placeholder'=>'Date To']) !!}
                <div class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></div>
            </div>
        </div> --}}

        <div class="form-group">
            <div class="input-group input-daterange">
                {!! Form::text('from','',['class' => 'form-control rticket-date','placeholder'=>'Date From']) !!}
                <div class="input-group-addon">to</div>
                {!! Form::text('to','',['class' => 'form-control rticket-date','placeholder'=>'Date To']) !!}
                
            </div>
        </div>
        {!! Form::submit('Add Service', ['class' => 'btn btn-info btn-block btn-lg']) !!}
    {!! Form::close() !!}
</div>
