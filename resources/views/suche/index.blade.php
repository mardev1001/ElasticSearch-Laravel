{{-- SUCHE --}}

@extends('master')

@section('content')


<div class="row">
    <div class="col-xs-12 col-md-12 white-bgrnd">
        <div class="fixed-row">
            <div class="fixed-position ">
                <h1 class="page-title">
                    {{ trans('sucheForm.search') }}
                </h1>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<h4>{{ trans('sucheForm.search-results') }}: 0 </h4>

<div class="row">
    <div class="col-xs-12">
        {{$result}}
    </div>
</div>

<div class="clearfix"></div> <br>

@stop
