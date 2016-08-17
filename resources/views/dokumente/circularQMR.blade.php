@extends('master')

@section('page-title')
     QM-Rundschreiben - Übersicht
@stop

    @section('content')

        <div class="row">
            <div class="col-xs-12 col-md-6 ">
                <div class="box-wrapper">
                    <h4 class="title">{{ trans('rundschreibenQmr.qmrEntwurf')}}</h4>
                    @if(count($qmrEntwurfPaginated))
                        <div class="box">
                            <div class="tree-view" data-selector="qmrEntwurfTree">
                                 <div class="qmrEntwurfTree hide" >{{ $qmrEntwurfTree }}</div>
                            </div>
                        </div>
                        <div class="text-center box box-pagination">
                            {!! $qmrEntwurfPaginated->render() !!}
                        </div>
                    @else
                        <div class="box">
                            <span class="text">Keine Dokumente gefunden.</span>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-xs-12 col-md-6 ">
                <div class="box-wrapper">
                    <h4 class="title">{{ trans('rundschreibenQmr.qmrFreigabe')}}</h4>
                    
                    @if(count($qmrFreigabePaginated))
                        <div class="box">
                            <div class="tree-view" data-selector="qmrFreigabeTree">
                                 <div class="qmrFreigabeTree hide" >{{ $qmrFreigabeTree }}</div>
                            </div>
                        </div>
                        <div class="text-center box box-pagination">
                            {!! $qmrFreigabePaginated->render() !!}
                        </div>
                    @else
                        <div class="box">
                            <span class="text">Keine Dokumente gefunden.</span>
                        </div>
                    @endif
                </div>
            </div>
        
        </div>
        
        <div class="clearfix"></div>
        
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box-wrapper">
                    <div class="box">
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
         </div>
        <div class="clearfix"></div>
        <br/>
            
      
        <div class="col-xs-12 col-md-12 box-wrapper">
            <h4 class="title">{{ trans('rundschreibenQmr.allQmr')}}</h4>
            @if(count($qmrAllPaginated))
                <div class="box">
                    <div class="tree-view" data-selector="qmrAllTree">
                         <div  class="qmrAllTree hide" >{{ $qmrAllTree }}</div>
                    </div>
                </div>
            
                <div class="text-center box box-pagination">
                    {!! $qmrAllPaginated->render() !!}
                </div>
            @else
                <div class="box">
                    <span class="text">Keine Dokumente gefunden.</span>
                </div>
            @endif
        </div>
        <div class="clearfix"></div>
    @stop
    