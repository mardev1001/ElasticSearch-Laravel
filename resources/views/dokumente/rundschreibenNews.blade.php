{{-- DOKUMENTE NEWS --}}

@extends('master')

@section('page-title')
    News - Übersicht
@stop


@section('content')

<div class="row">
    <div class="col-xs-12">
        <div class="box-wrapper">
            
            <h2 class="title">{{ trans('rundschreiben.meineNews') }}</h2>
            
            
            <div class="box">
                @if( count($rundschreibenMeine ) )
                    <div class="tree-view" data-selector="rundschreibenMeine">
                        <div class="rundschreibenMeine hide">
                            {{ $rundschreibenMeineTree }}
                        </div>
                    </div>
                
                 <div class="text-center">
                        {!! $rundschreibenMeine->render() !!}
                </div>
           
                @else
                    <span class="text">Keine Dokumente gefunden.</span>
                @endif 
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
                @if( count($rundschreibenAll ) )
                <div class="tree-view" data-selector="rundschreibenMeine">
                    <div class="rundschreibenMeine hide">
                        {{ $rundschreibenAllTree }}
                    </div>
                </div>
          
                 <div class="text-center">
                        {!! $rundschreibenAll->render() !!}
                </div>
     
                @else
                      <span class="text">Keine Dokumente gefunden.</span>
                @endif 
            </div>
        </div>
    </div>
</div>
   

<div class="clearfix"></div> <br>

@stop
