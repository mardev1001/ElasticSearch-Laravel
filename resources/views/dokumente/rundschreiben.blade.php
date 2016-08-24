{{-- DOKUMENTE RUNDSCHREIBEN --}}

@extends('master')

@section('page-title')
    Rundschreiben - Übersicht
@stop


@section('content')

{{-- compact('rundEntwurfPaginated', 'rundEntwurfTree', 'rundFreigabePaginated', 'rundFreigabeTree', 'rundAllPaginated', 'rundAllTree') --}}

<div class="row">
    
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper">
            
            <h2 class="title">{{ trans('rundschreiben.rundEntwurf') }}</h2>
            
            @if(count($rundEntwurfPaginated))
                
                <div class="box scrollable">
                    <div class="tree-view" data-selector="rundEntwurfTree">
                        <div class="rundEntwurfTree hide">
                            {{ $rundEntwurfTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center box box-pagination">
                    {!! $rundEntwurfPaginated->render() !!}
                </div>
            @else
                <div class="box">
                    <span class="text">Keine Dokumente gefunden.</span>
                </div>
            @endif
            
        </div>
        
    </div>
    
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper">
            
            <h2 class="title">{{ trans('rundschreiben.rundFreigabe') }}</h2>
            
            @if(count($rundFreigabePaginated))
            
                <div class="box scrollable">
                    <div class="tree-view" data-selector="rundFreigabeTree">
                        <div class="rundFreigabeTree hide">
                            {{ $rundFreigabeTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center box box-pagination">
                    {!! $rundFreigabePaginated->render() !!}
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

<div class="col-xs-12 box-wrapper">
    <div class="box">
        <div class="row">
            {!! Form::open(['action' => 'DocumentController@search', 'method'=>'POST']) !!}
                <div class="input-group">
                    <div class="col-md-12 col-lg-12">
                        {!! ViewHelper::setInput('search', '', old('search'), trans('navigation.search_placeholder'), trans('navigation.search_placeholder'), true) !!}
                        <input type="hidden" name="document_type_id" value="{{ $docType }}">
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
        <div class="box-wrapper">
            
            <h2 class="title">Alle Rundschreiben</h2>
            
            @if(count($rundAllPaginated))
            
                <div class="box scrollable">
                    <div class="tree-view" data-selector="rundAllPaginated">
                        <div class="rundAllPaginated hide">
                            {{ $rundAllTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center box box-pagination">
                    {!! $rundAllPaginated->render() !!}
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
