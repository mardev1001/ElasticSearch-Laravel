{{-- DOKUMENT TYPEN ÜBERSICHT --}}

@extends('master')
@section('page-title')
    @if(count($documentType))
        {{ ucfirst( trans('controller.dokumente')) }} - {{ $documentType->name }} 
    @else
        Dokument Typen
    @endif
@stop

@section('content')

{{-- compact('documentType', 'documentsByTypeTree', 'documentsByTypePaginated', 'docsByTypeEntwurfPaginated', 'docsByTypeEntwurfTree', 'docsByTypeFreigabePaginated', 'docsByTypeFreigabeTree' ) --}}

<!--
<div class="row">
    <div class="col-xs-12">
        <div class="box-wrapper">
            <h4 class="title">{{ trans('dokumentTypenForm.overview') }}</h4>
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
                    <div class="text-center ">
                        {!! $documentsByTypePaginated->render() !!}
                    </div>
                @else
                    Keine Daten gefunden.  
                @endif
            </div>
        </div>
    </div>
</div>
-->

<div class="row">
     @if( 
     ( $documentType->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
      ||  ( $documentType->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) ) 
     )
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper box-white">
            
            <h2 class="title">{{ trans('dokumentTypenForm.typesEntwurf') }}</h2>
            @if(count($docsByTypeEntwurfPaginated))
                <div class="box scrollable">
                    <div class="tree-view" data-selector="docsByTypeEntwurfTree">
                        <div class="docsByTypeEntwurfTree hide">
                            {{ $docsByTypeEntwurfTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center ">
                    {!! $docsByTypeEntwurfPaginated->render() !!}
                </div>
            @else
                <div class="box">
                    <span class="text">Keine Dokumente gefunden.</span>
                </div>
            @endif
        </div>
        
    </div>
    @endif
    
    @if( 
     ( $documentType->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
      ||  ( $documentType->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) ) 
     )
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper box-white">
            
            <h2 class="title">{{ trans('dokumentTypenForm.typesFreigabe') }}</h2>
            @if(count($docsByTypeFreigabePaginated))
                <div class="box scrollable">
                    <div class="tree-view" data-selector="docsByTypeFreigabeTree">
                        <div class="docsByTypeFreigabeTree hide">
                            {{ $docsByTypeFreigabeTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center ">
                    {!! $docsByTypeFreigabePaginated->render() !!}
                </div>
            @else
                <div class="box">
                    <span class="text">Keine Dokumente gefunden.</span>
                </div>
            @endif
        </div>
        
    </div>
    @endif
    
</div>

<div class="clearfix"></div> <br>

<div class="col-xs-12 box-wrapper box-white">
    <h2 class="title">{{ trans('benutzerForm.search') }} {{ $documentType->name }}</h2>
    <div class="box">
        <div class="row">
            {!! Form::open(['action' => 'DocumentController@search', 'method'=>'POST']) !!}
                <div class="input-group">
                    <div class="col-md-12 col-lg-12">
                        {!! ViewHelper::setInput('search', '', old('search'), trans('navigation.search_placeholder'), trans('navigation.search_placeholder'), true) !!}
                        <input type="hidden" name="document_type_id" value="{{ $documentType->id }}">
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <span class="custom-input-group-btn">
                            <button type="submit" class="btn btn-primary no-margin-bottom">
                                {{ trans('navigation.search') }} 
                            </button>
                        </span>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="clearfix"></div> <br>

<div class="row">
    
    <div class="col-xs-12">
        
        <div class="box-wrapper box-white">
            
            <h2 class="title">{{ trans('dokumentTypenForm.typesAll') ." ". $documentType->name }}</h2>
            @if(count($documentsByTypePaginated))
                <div class="box scrollable">
                    <div class="tree-view" data-selector="documentsByTypeTree">
                        <div class="documentsByTypeTree hide">
                            {{ $documentsByTypeTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center ">
                    {!! $documentsByTypePaginated->render() !!}
                </div>
            @else
                <div class="box">
                    <span class="text">Keine Dokumente gefunden.</span>
                </div>
            @endif
            
        </div>
        
    </div>
    
</div>

<div class="clearfix"></div> <br>

    
@stop
