@extends('master')
    @section('content')
     
        <div class="row">
            <div class="col-xs-12 col-md-12 white-bgrnd">
                <div class="fixed-row">
                    <div class="fixed-position ">
                        <h1 class="page-title">
                             {{ trans('controller.documentTemplates') }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
       
        <div class="clearfix"></div>
        
        <div class="col-xs-12 box-wrapper">  
            <div class="box">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <div class="tree-view" data-selector="test">
                             <div class="test hide" >{{$data}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="clearfix"></div>
        <br>
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div class="box-wrapper">
                    {!! Form::open([
                   'url' => '/search',
                   'method' => 'POST',
                   'class' => 'horizontal-form' ]) !!}
                        <!-- input box-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group">
                                    {!! ViewHelper::setInput('search',$data,old('search'),trans('navigation.search'),
                                    trans('navigation.search'), true) !!}
                    
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
                            <div class="col-md-5">
                                <div class="">
                                   
                                  
                                </div> 
                            </div>
                        </div><!--End input box-->
                   </form>
                </div>
            </div>
         </div>
        
        <div class="clearfix"></div>
        <br/>
        <div class="col-xs-12 box-wrapper">  
            <div class="box">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <h4 class="title">{{ trans('documentTemplates.allDocuments')}}</h4>
                        
                        <div class="tree-view" data-selector="test2">
                            <div class="test2 hide">{{$data2}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    @stop
    