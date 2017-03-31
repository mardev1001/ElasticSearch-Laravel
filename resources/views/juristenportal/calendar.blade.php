@extends('master')

@section('page-title'){{ ucfirst( trans('juristenPortal.juristenportal') )}} {{ ucfirst( trans('juristenPortal.calendar') )}}@stop

@section('content')

<div class="box-wrapper col-sm-12">
    <div class="box  box-white">
        <div class="row">
     
            <div class="box">
                
                <div class="row">
                    <div class="col-md-4 col-lg-3">
                          <div class="form-group">
                           {!! ViewHelper::setSelect($users,'user_id', '', old(''),
                            '', 'Mitarbeiter',true ) !!}
                            
                            
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