@extends('master')

@section('page-title')
     QM-Rundschreiben - Übersicht
@stop

    @section('content')

        <div class="col-xs-12 col-md-12 box-wrapper">
            <h4 class="title">{{ trans('rundschreibenQmr.myQmr')}}</h4>
            <div class="box">
                @if(count($qmrMyTree))
                    <div class="tree-view hide-icons" data-selector="qmrMyTree">
                         <div  class="qmrMyTree hide" >{{ $qmrMyTree }}</div>
                    </div>
                @else
                    Keine Daten gefunden.
                @endif
            </div>
            @if(count($qmrMyPaginated))
                <div class="text-center box box-pagination">
                    {!! $qmrMyPaginated->render() !!}
                </div>
            @endif
        </div>
        <div class="clearfix"></div>
        
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box-wrapper">
                    <div class="box">
                        <form method="GET" action="">
                            <!-- input box-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        {!! ViewHelper::setInput('search', '', old('search'),trans('navigation.search_placeholder'),
                                        trans('navigation.search_placeholder'), true) !!}
                                    </div>   
                                </div>
                               
                                <div class="col-md-12">
                                    <span class="custom-input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            {{ trans('navigation.search') }} 
                                        </button>
                                    </span>
                                </div><!--End input box-->
                                <!-- input box-->
                                <div class="col-md-6">
                                    <div class="">
                                        
                                      
                                    </div> 
                                </div>
                            </div><!--End input box-->
                       </form>
                    </div>
                </div>
            </div>
         </div>
        <div class="clearfix"></div>
        <br/>
            
      
        <div class="col-xs-12 col-md-12 box-wrapper">
            <h4 class="title">{{ trans('rundschreibenQmr.allQmr')}}</h4>
            <div class="box">
                @if(count($qmrMyTree))
                    <div class="tree-view hide-icons" data-selector="qmrAllTree">
                         <div  class="qmrAllTree hide" >{{ $qmrAllTree }}</div>
                    </div>
                @else
                    Keine Daten gefunden.
                @endif
            </div>
            @if(count($qmrMyPaginated))
                <div class="text-center box box-pagination">
                    {!! $qmrAllPaginated->render() !!}
                </div>
            @endif
        </div>
        <div class="clearfix"></div>
    @stop
    