@extends('master')
    @section('content')
        <h1 class="text-primary">
            {{ trans('controller.qmr') }}
        </h1>
        <div class="col-xs-12 col-md-12 box">
            <div class="tree-view" data-selector="test">
             <div  class="test hide" >{{$data}}</div>
            </div>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="col-xs-12 col-md-6">
            {!! Form::open([
           'url' => '/search',
           'method' => 'POST',
           'class' => 'horizontal-form' ]) !!}
                <!-- input box-->
                <div class="col-lg-6">
                    <div class="input-group">
                        {!! ViewHelper::setInput('search',$data,old('search'),trans('navigation.search'),
                        trans('navigation.search'), true) !!}
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">
                                <span class="fa fa-search"></span> {{ trans('navigation.search') }} 
                            </button>
                        </span>
                    </div>   
                </div><!--End input box-->
                <!-- input box-->
                <div class="col-lg-6">
                    <div class="form-group">
                        <br/>
                      
                    </div>   
                </div><!--End input box-->
           </form>
        </div>
        
        <div class="clearfix"></div>
        <br/>
            
      
        <div class="col-xs-12 col-md-12 box">
            <h4>{{ trans('rundschreibenQmr.allQmr')}}</h4>
            
            <div class="tree-view" data-selector="test2">
                <div class="test2 hide">{{$data2}}</div>
            </div>
        </div>
        <div class="clearfix"></div>
    @stop
    