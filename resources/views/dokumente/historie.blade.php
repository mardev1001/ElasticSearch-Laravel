{{-- HISTORIE --}}

@extends('master')

@section('content')

<div class="row">
    <div class="col-xs-12 col-md-12 ">
        <div class="fixed-row">
            <div class="fixed-position ">
                <h1 class="page-title">
                   {{ trans('controller.history') }} - Dokument QMR 123 - V2
                </h1>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        
        <h4>{{ trans('controller.overview') }}</h4>
        
        <div class="tree-view" data-selector="history">
            <div class="history hide">{{$data}}</div>
        </div>
        
    </div>
</div>

@stop
