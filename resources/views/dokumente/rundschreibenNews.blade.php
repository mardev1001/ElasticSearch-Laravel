{{-- DOKUMENTE NEWS --}}

@extends('master')

@section('page-title')
    News - Übersicht
@stop


@section('content')

{{-- compact('newsEntwurfPaginated', 'newsEntwurfTree', 'newsFreigabePaginated', 'newsFreigabeTree', 'newsAllPaginated', 'newsAllTree') --}}

<div class="row">
    
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper">
            
            <h2 class="title">{{ trans('rundschreiben.newsEntwurf') }}</h2>
            @if(count($newsEntwurfPaginated ))
                
                <div class="box">
                    <div class="tree-view" data-selector="newsEntwurfTree">
                        <div class="newsEntwurfTree hide">
                            {{ $newsEntwurfTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center box box-pagination">
                    {!! $newsEntwurfPaginated->render() !!}
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
            
            <h2 class="title">{{ trans('rundschreiben.newsFreigabe') }}</h2>
            
            @if(count($newsFreigabePaginated ))
                <div class="box">
                    
                        <div class="tree-view" data-selector="newsFreigabeTree">
                            <div class="newsFreigabeTree hide">
                                {{ $newsFreigabeTree }}
                            </div>
                        </div>
                     
                </div>
                
                <div class="text-center box box-pagination">
                    {!! $newsFreigabePaginated->render() !!}
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
            
            <h2 class="title">{{ trans('rundschreiben.allNews') }}</h2>
            
            @if(count($newsAllPaginated))
                <div class="box">
                    <div class="tree-view" data-selector="newsAllTree">
                        <div class="newsAllTree hide">
                            {{ $newsAllTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center box box-pagination">
                    {!! $newsAllPaginated->render() !!}
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
