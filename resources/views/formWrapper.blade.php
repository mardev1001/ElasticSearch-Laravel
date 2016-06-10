@extends('master')

@section('content') 
      
    <!--{{-- -->
    <!--<div class="row">-->
    <!--    <div class="col-xs-12 col-md-12 ">-->
    <!--        <div class="fixed-row">-->
    <!--            <div class="fixed-position ">-->
    <!--                <h1 class="page-title">-->
    <!--                     {{ $formWrapperData->buttonMethod }} {{ $formWrapperData->title }}-->
    <!--                </h1>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!--<div class="clearfix"></div>-->
    <!----}}-->
   
    <!--CreateMandantInfosTable
     <form action="{{$formWrapperData->controller.$formWrapperData->formUrl}}"
        method="{{$formWrapperData->method}}"
        class="horizontal-form'"
        id="{{$formWrapperData->fileUpload}}" >
              {{ csrf_field() }}-->
    @if( isset($method) )
     {!! Form::open([
           'url' => $formWrapperData->controller.$formWrapperData->formUrl,
           'method' => $method,
           'enctype' => 'multipart/form-data',
           'class' => 'horizontal-form',
           'id' => $formWrapperData->fileUpload]) !!}
    @else
     {!! Form::open([
           'url' => $formWrapperData->controller.$formWrapperData->formUrl,
           'method' => $formWrapperData->method,
           'enctype' => 'multipart/form-data',
           'class' => 'horizontal-form',
           'id' => $formWrapperData->fileUpload]) !!}
    @endif
    {!! Form::open([
           'url' => $formWrapperData->controller.$formWrapperData->formUrl,
           'method' => $formWrapperData->method,
           'enctype' => 'multipart/form-data',
           'class' => 'horizontal-form',
           'id' => $formWrapperData->fileUpload]) !!}
           
            @if( view()->exists($formWrapperData->controller.'.form') )
                @include($formWrapperData->controller.'.form')
            @else
                <div class="alert alert-warning">
                    <p> There is no form defined</p>      
                </div>
            @endif
           @if( view()->exists($formWrapperData->controller.'.form') )
                <div class="clearfix"></div>
                @yield('beforeButtons')
                    <div class="row">
                            <div class="col-xs-12 form-buttons">
                                <button class="btn btn-primary no-margin-bottom " type="submit">{{ $formWrapperData->buttonMethod }}</button>
                            </div>
                    </div>
                @yield('afterButtons') 
                
                @yield('closingElements')
            @endif
        </div> <!--end box-wrapper-->
    </form>
    @yield('closingElements')
    <div class="clearfix"></div>
      
    @stop
   