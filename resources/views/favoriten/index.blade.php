{{-- FAVORITEN --}}

@extends('master')

@section('content')

<h1 class="text-primary">{{ trans('controller.favorites') }}</h1>

<div class="row">
    <div class="col-xs-12">
        
        <h4>{{ trans('controller.overview') }}</h4>
        
        <div class="tree-view" data-selector="favorites">
            <div class="favorites hide">{{$data}}</div>
        </div>
        
    </div>
</div>
    
@stop
