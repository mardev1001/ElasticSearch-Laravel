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
    
</div>

<div class="clearfix"></div> <br>

<div class="col-xs-12 box-wrapper">
    <div class="box">
        <div class="row">
            {!! Form::open(['action' => 'DocumentController@search', 'method'=>'POST']) !!}
                <div class="input-group">
                    <div class="col-md-12 col-lg-12">
                        {!! ViewHelper::setInput('search', '', $search, trans('navigation.search_placeholder'), trans('navigation.search_placeholder'), true) !!}
                        @if(isset($docType)) <input type="hidden" name="document_type_id" value="{{ $docType }}"> @endif
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
            
            <h2 class="title">Alle @if($docTypeName) {{$docTypeName}} @elseDokumente @endif </h2>
            
            <div class="box">
                @if(isset($resultAllTree))
                <div class="tree-view" data-selector="rundschreibenMeine">
                    <div class="rundschreibenMeine hide">
                        {{ $resultAllTree }}
                    </div>
                </div>
                @else
                    Keine Daten gefunden.
                @endif
            </div>
             <div class="text-center">
                @if(isset($resultAllPaginated))
                    {{ $resultAllPaginated->render() }}
                @endif
            </div>
            
        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop
