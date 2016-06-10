{{-- HISTORIE --}}

@extends('master')

@section('page-title')
     {{ trans('controller.history') }} - Dokument QMR 123 - V2
@stop
@section('content')

<div class="row">
    <div class="col-xs-12">
        
        <h4>{{ trans('controller.overview') }}</h4>
        
        <div class="tree-view" data-selector="history">
            <div class="history hide">{{$data}}</div>
        </div>
        
    </div>
</div>

@stop
