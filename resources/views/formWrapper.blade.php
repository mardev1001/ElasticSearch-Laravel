@extends('master')

    @section('title')
     {{trans('controller.edit')}} {{trans('controller.'.$controller)}}
    @endsection
    
    @section('content') 
    <h1 class="text-primary">
          {{trans('controller.edit')}} {{trans('controller.'.$controller)}}
    </h1>
    {!! Form::open([
           'url' => $controller.$id,
           'method' => $method,
           'enctype' => 'multipart/form-data',
           'class' => 'dropzone horizontal-form',
           'id' => $file]) !!}

            @include($controller.'.'.$form)
            <div class="clearfix"></div>
            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <button class="btn btn-white" type="reset">Cancel</button>
                    <button class="btn btn-primary" type="submit">{{ $buttonText }} {{trans('controller.'.$controller)}}</button>
                </div>
            </div>
            <br/>
    </form>
    <div class="clearfix"></div>
      
    @stop
   