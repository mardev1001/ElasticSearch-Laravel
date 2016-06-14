@extends('master')

@section('page-title')
     QM-Rundschreiben - Übersicht
@stop

    @section('content')

        <div class="col-xs-12 col-md-12 box-wrapper">
            <h4 class="title">{{ trans('rundschreibenQmr.myQmr')}}</h4>
            <div class="box">
                <div class="tree-view hide-icons" data-selector="test">
                     <div  class="test hide" >{{$data}}</div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box-wrapper">
                    <div class="box">
                        {!! Form::open([
                       'url' => '/search',
                       'method' => 'POST',
                       'class' => 'horizontal-form' ]) !!}
                            <!-- input box-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        {!! ViewHelper::setInput('search',$data,old('search'),trans('navigation.search_placeholder'),
                                        trans('navigation.search_placeholder'), true) !!}
                        
                                    </div>   
                                </div>
                               
                                <div class="col-md-12">
                                    <span class="custom-input-group-btn">
                                        <button type="submit" class="btn btn-primary">
                                            <span class="fa fa-search"></span> {{ trans('navigation.search') }} 
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
                
                <div class="tree-view hide-icons" data-selector="test2">
                    <div class="test2 hide">{{$data2}}</div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    @stop
    