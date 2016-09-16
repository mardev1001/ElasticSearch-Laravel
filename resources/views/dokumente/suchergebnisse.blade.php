{{-- DOKUMENTE SUCHERGEBNISSE --}}

@extends('master')

@section('page-title')
    @if($docTypeName)
        {{$docTypeName}} - Übersicht
    @else
        Dokumente - Übersicht
    @endif
@stop


@section('content')


<div class="row">
    
    {{--
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper">
            
            <h2 class="title"> Meine @if($docTypeName) {{$docTypeName}} @else Dokumente @endif </h2>
            
            <div class="box">
                @if(isset($resultMyTree))
                    <div class="tree-view" data-selector="rundschreibenMeine">
                        <div class="rundschreibenMeine hide">
                            {{ $resultMyTree }}
                        </div>
                    </div>
                @else
                    Keine Daten gefunden.        
                @endif
            </div>
            <div class="text-center">
                @if(isset($resultMyPaginated))
                    {!! $resultMyPaginated->render() !!}
                @endif
            </div>
            
        </div>
    </div>
    --}}
    
     @if( 
     ( $docTypeSearch->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
      ||  ( $docTypeSearch->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) )
     )
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper">
            
            <h2 class="title">{{ trans('rundschreiben.rundEntwurf') }}</h2>
            
            @if(count($searchEntwurfPaginated))
                
                <div class="box scrollable">
                    <div class="tree-view" data-selector="searchEntwurfTree">
                        <div class="searchEntwurfTree hide">
                            {{ $searchEntwurfTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center box box-pagination">
                    {!! $searchEntwurfPaginated->render() !!}
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
     ( $docTypeSearch->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
      ||  ( $docTypeSearch->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) )
     )
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper">
            
            <h2 class="title">{{ trans('rundschreiben.rundFreigabe') }}</h2>
            
            @if(count($searchFreigabePaginated))
            
                <div class="box scrollable">
                    <div class="tree-view" data-selector="searchFreigabeTree">
                        <div class="searchFreigabeTree hide">
                            {{ $searchFreigabeTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center box box-pagination">
                    {!! $searchFreigabePaginated->render() !!}
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

<div id="search-form" class="col-xs-12 box-wrapper">
    <div class="box">
        <div class="row">
            {!! Form::open(['action' => 'DocumentController@search', 'method'=>'POST']) !!}
                <div class="input-group">
                    <div class="col-md-12 col-lg-12">
                        {!! ViewHelper::setInput('search', '', $search, trans('navigation.search_placeholder'), trans('navigation.search_placeholder'), true) !!}
                        @if(isset($docType)) <input type="hidden" name="document_type_id" value="{{ $docType }}"> @endif
                        @if(isset($iso_category_id)) <input type="hidden" name="iso_category_id" value="{{ $iso_category_id }}"> @endif
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
        <div class="col-xs-12 box-wrapper">
            
            <h2 class="title">Alle @if($docTypeName) {{$docTypeName}} @else Dokumente @endif </h2>
            
            @if(count($resultAllPaginated))
                <div class="box scrollable">
                    <div class="tree-view" data-selector="rundschreibenMeine">
                        <div class="rundschreibenMeine hide">
                            {{ $resultAllTree }}
                        </div>
                    </div>
                </div>
                <div class="text-center box box-pagination">
                    {{ $resultAllPaginated->render() }}
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
