{{-- FAVORITEN --}}

@extends('master')

@section('content')

<div class="row">
    <div class="col-xs-12 col-md-12 white-bgrnd">
        <div class="fixed-row">
            <div class="fixed-position ">
                <h1 class="page-title">
                    {{ trans('controller.favorites') }}
                </h1>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="row ">
    <div class="col-xs-12 col-md-3">
        <div class="test">
            <div>
                <input id="radio-1" class="radio-custom" name="radio-group" type="radio" checked>
                <label for="radio-1" class="radio-custom-label">First Choice</label>
            </div>
            <div>
                <input id="radio-2" class="radio-custom" name="radio-group" type="radio">
                <label for="radio-2" class="radio-custom-label">Second Choice</label>
            </div>
            
             <div class="checkbox">
                   <br><input type="checkbox" value="1" name="active" id="active" checked><label for="active">{{ trans('benutzerForm.active') }}</label>
                </div>
                
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box-wrapper">
            <h4 class="title">{{ trans('controller.overview') }}</h4>
            
            <div class="tree-view" data-selector="favorites">
                <div class="favorites hide">{{$data}}</div>
            </div>
        </div>
    </div>
</div>
    
@stop
