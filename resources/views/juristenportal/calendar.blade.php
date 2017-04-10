@extends('master')

@section('page-title'){{ ucfirst( trans('juristenPortal.juristenportal') )}} {{ ucfirst( trans('juristenPortal.calendar') )}}@stop

@section('content')

<div class="box-wrapper col-sm-12">
    <div class="box  box-white">
        <div class="row">
     
            <div class="box">
                 
                
                <div class="row">
                    {!! Form::open(['action' => 'JuristenPortalController@viewUserCalendar', 'method'=>'POST']) !!}
                    <input type="hidden" id="starViewtDate" name="starViewtDate" value="">
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

$( 'select[name=id]' ).change(function() {
    var start = $('#calendar').fullCalendar('getDate');
    $('#starViewtDate').val(start.format());
});


$('#calendar').fullCalendar({

    defaultDate: moment('{{  $startdate or Carbon\Carbon::today()->format("Y-m-d") }}'),
    
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
        
        var startdate = $('#calendar').fullCalendar('getDate');
        $('#starViewtDate').val(startdate.format());
        
        var user_id = $('select[name=id]').val();
        
        jQuery.ajax({
            url: './calendarEvent',
            type: 'POST',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                start: start.format(),
                end: end.format(),
                user_id: user_id
            },
            success: function(doc) {
                
                var events = [];
                
                for(var i = 0; i < doc.length; i++){
                    var item = {};
                    item.id = doc[i].id;
                    item.title = doc[i].title;
                    item.start = doc[i].start;
                    item.color = doc[i].color;
                    item.textColor = '#FFF';
                    events.push(item);
                }

                callback(events);
            }
        });
    }
});
</script>

@stop