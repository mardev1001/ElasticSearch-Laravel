@extends('master')
    @section('content') 
    <h1 class="text-primary">
        {{-- dd($formWrapperData) --}}
        {{ $formWrapperData->buttonMethod }} {{ $formWrapperData->title }}
    </h1>
    {!! Form::open([
           'url' => $formWrapperData->controller.$formWrapperData->formUrl,
           'method' => $formWrapperData->method,
           'enctype' => 'multipart/form-data',
           'class' => 'dropzone horizontal-form',
           'id' => $formWrapperData->fileUpload]) !!}
            
            @if( view()->exists($formWrapperData->controller.'.form') )
                @include($formWrapperData->controller.'.form')
            @else
                <div class="alert alert-warning">
                    <p> There is no form defined</p>      
                </div>
            @endif
           
            <div class="clearfix"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-white" type="reset">{{ trans('formWrapper.cancel') }}</button>
                    <button class="btn btn-primary" type="submit">{{ $formWrapperData->buttonMethod }} {{ strtolower( $formWrapperData->title ) }}</button>
                </div>
            </div>
            <br/>
    </form>
    <div class="clearfix"></div>
      
    @stop
   