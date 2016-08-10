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
            
            <div class="box">
                @if( count($newsEntwurfTree ) )
                    <div class="tree-view" data-selector="newsEntwurfTree">
                        <div class="newsEntwurfTree hide">
                            {{ $newsEntwurfTree }}
                        </div>
                    </div>
                @else
                    <span class="text">Keine Dokumente gefunden.</span>
                @endif 
            </div>
            
            <div class="text-center box box-pagination">
                {!! $newsEntwurfPaginated->render() !!}
            </div>
            
        </div>
        
    </div>
    
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper">
            
            <h2 class="title">{{ trans('rundschreiben.newsFreigabe') }}</h2>
            
            <div class="box">
                @if( count($newsFreigabeTree ) )
                    <div class="tree-view" data-selector="newsFreigabeTree">
                        <div class="newsFreigabeTree hide">
                            {{ $newsFreigabeTree }}
                        </div>
                    </div>
                @else
                    <span class="text">Keine Dokumente gefunden.</span>
                @endif 
            </div>
            
            <div class="text-center box box-pagination">
                {!! $newsFreigabePaginated->render() !!}
            </div>
            
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
            
            <div class="box">
                @if( count($newsAllTree) )
                    <div class="tree-view" data-selector="newsAllTree">
                        <div class="newsAllTree hide">
                            {{ $newsAllTree }}
                        </div>
                    </div>
                @else
                      <span class="text">Keine Dokumente gefunden.</span>
                @endif 
            </div>
            
            <div class="text-center box box-pagination">
                {!! $newsAllPaginated->render() !!}
            </div>
                
        </div>
    </div>
</div>
   

<div class="clearfix"></div> <br>

@stop
