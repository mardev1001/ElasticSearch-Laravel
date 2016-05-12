@extends('master')
    @section('content') 
    <h1 class="text-primary">
        {{ $formWrapperData->buttonMethod }} {{ $formWrapperData->title }}
    </h1>
    <!--CreateMandantInfosTable
     <form action="{{$formWrapperData->controller.$formWrapperData->formUrl}}"
        method="{{$formWrapperData->method}}"
        class="horizontal-form'"
        id="{{$formWrapperData->fileUpload}}" >
              {{ csrf_field() }}-->
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
           
            <div class="clearfix"></div>
            <div class="col-xs-12 form-buttons">
                <button class="btn btn-white" type="reset">{{ trans('formWrapper.reset') }}</button>
                <button class="btn btn-primary" type="submit">{{ $formWrapperData->buttonMethod }}</button>
            </div>
            <br/>
    </form>
    <div class="clearfix"></div>
      
    @stop
   