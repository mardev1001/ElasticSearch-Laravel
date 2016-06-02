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
