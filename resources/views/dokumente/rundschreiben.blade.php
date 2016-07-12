{{-- DOKUMENTE RUNDSCHREIBEN --}}

@extends('master')

@section('page-title')
    Rundschreiben - Übersicht
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper">
            
            <h2 class="title">Meine Rundschreiben</h2>
            
            <div class="box">
                <div class="tree-view" data-selector="rundschreibenMeine">
                    <div class="rundschreibenMeine hide">
                        {{ $rundschreibenMeineTree }}
                    </div>
                </div>
            </div>
            <div class="text-center">
                {!! $rundschreibenMeine->render() !!}
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
        <div class="col-xs-12 box-wrapper">
            
            <h2 class="title">Alle Rundschreiben</h2>
            
            <div class="box">
                <div class="tree-view" data-selector="rundschreibenMeine">
                    <div class="rundschreibenMeine hide">
                        {{ $rundschreibenAllTree }}
                    </div>
                </div>
            </div>
             <div class="text-center">
                    {!! $rundschreibenAll->render() !!}
            </div>
            
        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop
