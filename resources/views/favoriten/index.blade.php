{{-- FAVORITEN --}}

@extends('master')
@section('page-title') {{ trans('controller.favorites') }} @stop

@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="box-wrapper">
            <h4 class="title">{{ trans('controller.overview') }}</h4>
            <div class="box">
                <div class="tree-view hide-icons" data-selector="favorites">
                    <div class="favorites hide">{{$data}}</div>
                </div>
            </div>
        </div>
    </div>
</div>
    
@stop
