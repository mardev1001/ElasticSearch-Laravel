@extends('master')

@section('page-title') Formulare - Übersicht @stop

@section('content')

{{-- compact('docType', 'formulareAllPaginated', 'formulareAllTree', 'formulareEntwurfPaginated', 'formulareEntwurfTree', 'formulareFreigabePaginated', 'formulareFreigabeTree') --}}

<div class="row">
    
    <div class="col-xs-12 col-md-6">
        <div class="box-wrapper">
            <h2 class="title">{{ trans('documentTemplates.templateEntwurf') }}</h2>
            @if(count($formulareEntwurfPaginated))
                <div class="box">
                    <div class="tree-view" data-selector="formulareEntwurfTree">
                        <div class="formulareEntwurfTree hide">
                            {{ $formulareEntwurfTree }}
                        </div>
                    </div>
                    <div class="text-center">
                        {!! $formulareEntwurfPaginated->render() !!}
                    </div>
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
            <h2 class="title">{{ trans('documentTemplates.templateFreigabe') }}</h2>
                @if(count($formulareFreigabePaginated))
                    <div class="box">
                        <div class="tree-view" data-selector="formulareFreigabeTree">
                            <div class="formulareFreigabeTree hide">
                                {{ $formulareFreigabeTree }}
                            </div>
                        </div>
                        <div class="text-center">
                            {!! $formulareFreigabePaginated->render() !!}
                        </div>
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

<div class="row">
    <div class="col-xs-12">
        <div class="box-wrapper">
            <div class="box">
                
                <div class="row">
                    <form action="" method="GET">
                        <div class="input-group">
                            <div class="col-md-12 col-lg-12">
                                {!! ViewHelper::setInput('search', '', old('search'), trans('navigation.newsSearchPlaceholder'), trans('navigation.newsSearchPlaceholder'), true) !!}
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <span class="custom-input-group-btn">
                                    <button type="submit" class="btn btn-primary no-margin-bottom">
                                        {{ trans('navigation.search') }} 
                                    </button>
                                </span>
                            </div>
                        </div>
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="clearfix"></div> <br>

<div class="row">
    <div class="col-xs-12">
        <div class="box-wrapper">
            <h2 class="title">{{ trans('documentTemplates.allDocuments') }}</h2>
            
            @if(count($formulareAllPaginated))
                <div class="box">
                    <div class="tree-view" data-selector="formulareAllPaginated">
                        <div class="formulareAllPaginated hide">
                            {{ $formulareAllTree }}
                        </div>
                    </div>
                    <div class="text-center">
                        {!! $formulareAllPaginated->render() !!}
                    </div>
                </div>
            @else
                <div class="box">
                    <span class="text">Keine Dokumente gefunden.</span>
                </div>
            @endif
            
        </div>
    </div>
</div>

<div class="clearfix"></div><br/>

@stop
