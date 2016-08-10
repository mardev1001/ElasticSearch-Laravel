@extends('master')

@section('page-title')
     QM-Rundschreiben - Übersicht
@stop

    @section('content')

        <div class="row">
            <div class="col-xs-12 col-md-6 ">
                <div class="box-wrapper">
                    <h4 class="title">{{ trans('rundschreibenQmr.qmrEntwurf')}}</h4>
                    <div class="box">
                        @if(count($qmrEntwurfTree))
                            <div class="tree-view" data-selector="qmrEntwurfTree">
                                 <div  class="qmrEntwurfTree hide" >{{ $qmrEntwurfTree }}</div>
                            </div>
                        @else
                            Keine Daten gefunden.
                        @endif
                    </div>
                    @if(count($qmrEntwurfPaginated))
                        <div class="text-center box box-pagination">
                            {!! $qmrEntwurfPaginated->render() !!}
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="col-xs-12 col-md-6 ">
                <div class="box-wrapper">
                    <h4 class="title">{{ trans('rundschreibenQmr.qmrFreigabe')}}</h4>
                    <div class="box">
                        @if(count($qmrFreigabeTree))
                            <div class="tree-view" data-selector="qmrFreigabeTree">
                                 <div  class="qmrFreigabeTree hide" >{{ $qmrFreigabeTree }}</div>
                            </div>
                        @else
                            Keine Daten gefunden.
                        @endif
                    </div>
                    @if(count($qmrFreigabePaginated))
                        <div class="text-center box box-pagination">
                            {!! $qmrFreigabePaginated->render() !!}
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
            <div class="box">
                @if(count($qmrAllTree))
                    <div class="tree-view" data-selector="qmrAllTree">
                         <div  class="qmrAllTree hide" >{{ $qmrAllTree }}</div>
                    </div>
                @else
                    Keine Daten gefunden.
                @endif
            </div>
            @if(count($qmrAllPaginated))
                <div class="text-center box box-pagination">
                    {!! $qmrAllPaginated->render() !!}
                </div>
            @endif
        </div>
        <div class="clearfix"></div>
    @stop
    