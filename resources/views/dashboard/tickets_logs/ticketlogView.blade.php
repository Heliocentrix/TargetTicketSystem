<div class="container-ticker-report" style="margin-top: 35px;position: relative;display: inline-block;">
    <div class="col-md-6 col-md-offset-3">
        {!! Form::open(['route' => 'ticket_logs.export']) !!}
            <div class="form-group">
                <div class="input-group input-daterange" id="rticket-date-range">
                    {!! Form::text('from','',['class' => 'form-control rticket-date','placeholder'=>'Date From', 'id'=>'dfrom']) !!}
                    <div class="input-group-addon">to</div>
                    {!! Form::text('to','',['class' => 'form-control rticket-date','placeholder'=>'Date To', 'id'=>'to']) !!}
                </div>
            </div>
            {!! Form::submit('Export', ['class' => 'btn btn-info btn-block btn-lg']) !!}
        {!! Form::close() !!}
    </div>
</div>