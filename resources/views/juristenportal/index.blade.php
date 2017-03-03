{{-- Client managment--}}

@extends('master')

@section('page-title')
    {{ ucfirst( trans('navigation.juristenPortal') )}}
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper box-white">
            
            <h2 class="title">{{ ucfirst( trans('juristenPortal.juristenportal') )}}</h2>
            
            <div class="box iso-category-overview">
                
                <ul class="level-1">
                    <li>
                        <a href="{{ url('#') }}">{{ ucfirst( trans('juristenPortal.documents') ) }} </a>
                    </li>
                    <li>
                        <a href="{{ url('juristenportal/upload') }}">{{ ucfirst( trans('juristenPortal.upload') ) }} </a>
                    </li>
                    <li>
                        <a href="{{ url('#') }}">{{ ucfirst( trans('juristenPortal.calendar') ) }} </a>
                    </li>
                    <li>
                        <a href="{{ url('#') }}">{{ ucfirst( trans('juristenPortal.files') ) }} </a>
                    </li>
                    <li>
                        <a href="{{ url('#') }}">{{ ucfirst( trans('juristenPortal.createFile') ) }} </a>
                    </li>
                </ul>
                
            </div>

        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop