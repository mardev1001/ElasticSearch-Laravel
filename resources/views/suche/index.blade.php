{{-- SUCHE --}}

@extends('master')

@section('content')

<h1 class="text-primary">{{ trans('sucheForm.search') }}</h1>


    <h4>{{ trans('sucheForm.search-results') }}: 0 </h4>
    
    <div class="row">
        <div class="col-xs-12">
            {{$result}}
        </div>
    </div>

<div class="clearfix"></div> <br>

@stop
