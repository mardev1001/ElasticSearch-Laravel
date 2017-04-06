@extends('master')

@section('page-title'){{ ucfirst( trans('juristenPortal.juristenportal') )}} {{ ucfirst( trans('juristenPortal.calendar') )}}@stop

@section('content')

<div class="box-wrapper col-sm-12">
    <div class="box  box-white">
        <div class="row">
     
            <div class="box">
                 
                
                <div class="row">
                    {!! Form::open(['action' => 'JuristenPortalController@viewUserCalendar', 'method'=>'POST']) !!}
                    <div class="col-md-4 col-lg-3">
                          <div class="form-group">
                           {!! ViewHelper::setUserSelect($users,'id', $data, old('users'),'', 'Mitarbeiter',false ) !!}
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label>&nbsp;</label>  
                        <div class="form-group">
                           <button class="btn btn-primary" type="submit">anzeigen</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <div class="col-md-4 col-lg-3">
                    </div>
                    <div class="col-md-4 col-lg-3 text-right">
                        <label>&nbsp;</label>  
                        <div class="form-group">
                            <a href="#" id="my-next-button">test next</a>
                            <button class="btn btn-primary" type="submit">next</button>
                        </div>
                    </div>
                </div>    
                
                
                <div id='calendar'></div>
                
            </div>

        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>
@stop

@section('script')
<script>

$('#my-next-button').click(function() {
    $('#calendar').fullCalendar('next');
        var moment = $('#calendar').fullCalendar('getDate');
    alert("The current date of the calendar is " + moment.format());
});

$('#calendar').fullCalendar({
    
    header: {
				left: 'prev,next, today',
				center: 'title',
				right: 'listMonth,month'
			},
    views: {
				listMonth: { buttonText: 'Listenansicht' },
				month: { buttonText: 'Kalender' }
			},
			
    eventLimit: 6, // for all non-agenda views
    
    events: function(start, end, timezone, callback) {
        
        jQuery.ajax({
            url: './calendarEvent',
            type: 'POST',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                start: start.format(),
                end: end.format()
            },
            success: function(doc) {
                console.log(doc.title);
                var events = [];
               // if(!!doc.result){
                    $.map( doc, function() {
                        events.push({
                            id: '1',
                            title: doc.title,
                            start: '2017-04-05',
                            end: '2017-04-05'
                        });
                    });
               // }
                callback(events);
            }
        });
    }
});
</script>

@stop