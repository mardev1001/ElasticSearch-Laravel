@extends('master')

@section('page-title'){{ ucfirst( trans('juristenPortal.juristenportal') )}} {{ ucfirst( trans('juristenPortal.calendar') )}}@stop

@section('content')

<div class="box-wrapper col-sm-12">
    <div class="box  box-white">
        <div class="row">
     
            <div class="box">
                 
                {!! Form::open(['action' => 'JuristenPortalController@viewUserCalendar', 'method'=>'POST']) !!}
                <div class="row">
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
                    <div class="col-md-4 col-lg-3">
                    </div>
                    <div class="col-md-4 col-lg-3 text-right">
                        <label>&nbsp;</label>  
                        <div class="form-group">
                        </div>
                    </div>
                </div>    
                {!! Form::close() !!} 
                
                <div id='calendar'></div>
                
            </div>

        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>
@stop

@section('script')
<script>
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

    eventSources: [

        {
            events: [ // put the array in the `events` property
                {
                    title  : 'Test event 1',
                    start  : '2017-04-05'
                },
                {
                    title  : 'event2',
                    start  : '2017-04-05',
                    end    : '2017-04-07'
                },
                {
                    title  : 'event3',
                    start  : '2017-04-05T12:30:00',
                },
                {
                    title  : 'Test event 4',
                    start  : '2017-04-05'
                },
                {
                    title  : 'event5',
                    start  : '2017-04-05',
                    end    : '2017-04-05'
                },
                {
                    title  : 'event6',
                    start  : '2017-04-05T12:30:00',
                }
            ],
            color: 'blau',     // an option!
            textColor: '#FAA' // an option!
        },
        
        {
            events: [ // put the array in the `events` property
                {
                    title  : 'Test event 7',
                    start  : '2017-04-05'
                },
                {
                    title  : 'event8',
                    start  : '2017-04-05',
                    end    : '2017-04-05'
                },
                {
                    title  : 'event9',
                    start  : '2017-04-05T12:30:00',
                }
            ],
            color: 'red',     // an option!
            textColor: '#FAA' // an option!
        },
        
        {
            events: [ // put the array in the `events` property
                {
                    title  : 'Test event 10',
                    start  : '2017-04-05'
                },
                {
                    title  : 'event11',
                    start  : '2017-04-05',
                    end    : '2017-04-05'
                },
                {
                    title  : 'event12',
                    start  : '2017-04-05T12:30:00',
                }
            ],
            color: 'green',     // an option!
            textColor: '#FAA' // an option!
        },
        
         {
            events: [ // put the array in the `events` property
                {
                    title  : 'Test event 2',
                    start  : '2017-04-20'
                },
                {
                    title  : 'event2',
                    start  : '2017-04-20',
                    end    : '2017-04-20'
                },
                {
                    title  : 'event3',
                    start  : '2010-01-09T12:30:00',
                }
            ],
            color: 'red',     // an option!
            textColor: '#FAA' // an option!
        }


    ]

});    
</script>

@stop