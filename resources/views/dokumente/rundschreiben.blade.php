@extends('master')
    @section('content')
        <h1 class="text-primary">
            {{ trans('controller.rundschreiben') }}
        </h1>
        <div class="col-xs-12 col-md-6 box">
            <h2>QMR 223 <small>({{ trans('rundschreiben.version') }}: 2 )</small> <small><b>{{ trans('rundschreiben.status') }}:</b> Veröffentlicht</small></h2>
        </div>
        <div class="col-xs-12 col-md-6 box">
            
                <a href="#" class="btn btn-primary"><span class="fa fa-edit"></span> {{ trans('rundschreiben.edit') }}</a> 
                
                <a href="#" class="btn btn-danger"><span class="fa fa-trash"></span> {{ trans('rundschreiben.delete') }}</a>
            
                <a href="#" class="btn btn-info"><span class="fa fa-files-o"></span> {{ trans('rundschreiben.newVersion') }}</a>
           
                <a href="#" class="btn btn-info"><span class="fa fa-history"></span> {{ trans('rundschreiben.history') }}</a>
          
                <a href="#" class="btn btn-info"><span class="fa fa-star"></span> {{ trans('rundschreiben.favorite') }}</a>
        </div>
        <div class="clearfix"></div>
         <div class="col-xs-12 col-md-12 box">
             <h3>Betreff </h3>
             <h4>Headline</h4>
             <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut
             labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. 
             Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, 
             consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam 
             voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus
             est Lorem ipsum dolor sit amet.
             </p>
         </div>
        
        <div class="col-xs-12 col-md-6 box">
            <div class="tree-view" data-selector="test">
             <div  class="test hide" >{{$data}}</div>
            </div>
        </div>
        
        
        <div class="clearfix"></div>
        <br/>
            
      
        <div class="col-xs-12 col-md-12 box">
            <h4>{{ trans('rundschreiben.userComments')}} 
            <span class="pull-right">{!! ViewHelper::setCheckbox('deletedComments', $data, old('deletedComments'), trans('rundschreiben.deletedComments'), trans('rundschreiben.deletedComments'), false) !!}
             </span>
            </h4>
            
            <div class="tree-view" data-selector="test2">
                <div class="test2 hide">{{$data2}}</div>
            </div>
        </div>
        <div class="clearfix"></div>
        <br/>
         <div class="col-xs-12 col-md-6 box">
            
                <a href="#" class="btn btn-primary"><span class="fa fa-file-pdf-o"></span> {{ trans('rundschreiben.pdf') }}</a> 
            
                <a href="#" class="btn btn-primary"><span class="fa fa-comment-o"></span>  {{ trans('rundschreiben.comment') }}</a>
           
                <a href="#" class="btn btn-primary"><span class="fa fa-share-alt"></span>  {{ trans('rundschreiben.release') }}</a>
          
                <a href="#" class="btn btn-danger"><span class="fa fa-share-alt-square"></span> {{ trans('rundschreiben.noRelease') }}</a>
        </div>
    @stop
    