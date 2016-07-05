@extends('master')

@section('page-title') Formulare - Übersicht @stop

@section('content')
    <div class="row">
    <div class="col-xs-12">
        <div class="box-wrapper">
            
            <h2 class="title">{{ trans('documentTemplates.myDocuments') }}</h2>
            
            
            <div class="box">
                @if( count($formulareMeine ) )
                    <div class="tree-view" data-selector="rundschreibenMeine">
                        <div class="rundschreibenMeine hide">
                            {{ $formulareMeineTree }}
                        </div>
                    </div>
                
                 <div class="text-center">
                        {!! $formulareMeine->render() !!}
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
            
            <h2 class="title">{{ trans('documentTemplates.allDocuments') }}</h2>
            
            <div class="box">
                @if( count($formulareAll ) )
                <div class="tree-view" data-selector="rundschreibenMeine">
                    <div class="rundschreibenMeine hide">
                        {{ $formulareAllTree }}
                    </div>
                </div>
          
                 <div class="text-center">
                        {!! $formulareAll->render() !!}
                </div>
     
                @else
                      <span class="text">Keine Dokumente gefunden.</span>
                @endif 
            </div>
        </div>
    </div>
</div>

        
        
       
        
        <div class="clearfix"></div><br/>
    @stop
    