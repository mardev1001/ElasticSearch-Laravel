{{-- ISO DOKUMENTE --}}

@extends('master')

@section('page-title')
    ISO Dokumente - Übersicht
@stop

@section('content')

<div class="clearfix"></div> 

<div class="row">
    
    <div class="col-xs-12 col-md-6">
        <div class="box-wrapper">
            <h2 class="title">{{ trans('isoDokument.isoEntwurf') }}</h2>
            @if(count($isoEntwurfPaginated))
                <div class="box">
                    <div class="tree-view" data-selector="isoEntwurfTree">
                        <div class="isoEntwurfTree hide">
                            {{ $isoEntwurfTree }}
                        </div>
                    </div>
                </div>
                <div class="text-center box box-pagination">
                    {!! $isoEntwurfPaginated->render() !!}
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
            <h2 class="title">{{ trans('isoDokument.isoFreigabe') }}</h2>
            @if(count($isoFreigabePaginated))
                <div class="box">
                    <div class="">
                        <div class="tree-view" data-selector="isoFreigabeTree">
                            <div class="isoFreigabeTree hide">
                                {{ $isoFreigabeTree }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center box box-pagination">
                    {!! $isoFreigabePaginated->render() !!}
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
    <div class="search box">
        <div class="row">
            <form action="" method="GET">
                <div class="input-group">
                    <div class="col-md-12 col-lg-12">
                        {!! ViewHelper::setInput('search', '', old('search'), trans('navigation.search_placeholder'), trans('navigation.search_placeholder'), true) !!}
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

<div class="clearfix"></div> <br>

<div class="row">
    
    <div class="col-xs-12">
        <div class="box-wrapper">
            <h2 class="title">Alle ISO Dokumente</h2>
                @if(count($isoAllPaginated))
                    <div class="box">
                        <div class="tree-view" data-selector="isoAllTree">
                            <div class="isoAllTree hide">
                                {{ $isoAllTree }}
                            </div>
                        </div>
                        <div class="text-center">
                            {!! $isoAllPaginated->render() !!}
                        </div>
                    </div>
                @else
                    <div class="box">
                        <span>Keine Dokumente gefunden.</span>
                    </div>
                @endif
             </div>   
        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop
