{{-- DOKUMENT TYPEN ÜBERSICHT --}}

@extends('master')
@section('page-title')
    @if(count($documentType))
        {{ $documentType->name }}
    @else
        Dokument Typen
    @endif
@stop

@section('content')


<div class="row">
    <div class="col-xs-12">
        <div class="box-wrapper">
            <h4 class="title">{{ trans('controller.overview') }}</h4>
            <div class="box">
                @if(count($documentType))
                    <div class="tree-view hide-icons" data-selector="documentsByTypeTree">
                        @if(count($documentsByTypeTree))
                            <div class="documentsByTypeTree hide">
                                {{$documentsByTypeTree}}
                            </div>
                        @else
                            Keine Daten gefunden.
                        @endif
                    </div>
                    <div class="text-center box box-pagination">
                        {!! $documentsByTypePaginated->render() !!}
                    </div>
                @else
                    Keine Daten gefunden.  
                @endif
            </div>
        </div>
    </div>
</div>

    
@stop
