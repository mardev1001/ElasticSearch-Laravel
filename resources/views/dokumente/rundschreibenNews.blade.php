{{-- DOKUMENTE NEWS --}}

@extends('master')

@section('page-title')
    News - Übersicht
@stop


@section('content')

{{-- compact('newsEntwurfPaginated', 'newsEntwurfTree', 'newsFreigabePaginated', 'newsFreigabeTree', 'newsAllPaginated', 'newsAllTree') --}}

<div class="row">
    
    @if( 
     ( $docType->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
      ||  ( $docType->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) )
     )
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper box-white">
            
            <h2 class="title">{{ trans('rundschreiben.newsEntwurf') }}</h2>
            @if(count($newsEntwurfPaginated ))
                
                <div class="box scrollable">
                    <div class="tree-view" data-selector="newsEntwurfTree">
                        <div class="newsEntwurfTree hide">
                            {{ $newsEntwurfTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    {!! $newsEntwurfPaginated->render() !!}
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
     ( $docType->document_art == 1 &&  ViewHelper::universalHasPermission( array(13) ) == true )
      ||  ( $docType->document_art == 0 && ( ViewHelper::universalHasPermission( array(11) ) == true) )
     )
    <div class="col-xs-12 col-md-6">
        
        <div class="box-wrapper box-white">
            
            <h2 class="title">{{ trans('rundschreiben.newsFreigabe') }}</h2>
            
            @if(count($newsFreigabePaginated ))
                <div class="box scrollable">
                    
                        <div class="tree-view" data-selector="newsFreigabeTree">
                            <div class="newsFreigabeTree hide">
                                {{ $newsFreigabeTree }}
                            </div>
                        </div>
                     
                </div>
                
                <div class="text-center">
                    {!! $newsFreigabePaginated->render() !!}
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

<div class="row">
    <div class="col-xs-12">
        <div class="box-wrapper box-white">
            <div class="box">
                
                <div class="row">
                    {!! Form::open(['action' => 'DocumentController@search', 'method'=>'POST']) !!}
                    <input type="hidden" name="document_type_id" value="{{ $docType->id }}">
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
        <div class="box-wrapper box-white">
            
            <h2 class="title">{{ trans('rundschreiben.allNews') }}</h2>
            
            @if(count($newsAllPaginated))
                <div class="box scrollable">
                    <div class="tree-view" data-selector="newsAllTree">
                        <div class="newsAllTree hide">
                            {{ $newsAllTree }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
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
