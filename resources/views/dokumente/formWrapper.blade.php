@extends('master')
    @section('content') 
    <h1 class="text-primary">
        
    </h1>
    {!! Form::open([
           'url' => $url,
           'method' => 'POST',
           'enctype' => 'multipart/form-data',
           'class' => 'horizontal-form']) !!}
           
            @if( view()->exists('dokumente.'.$form) )
                @include('dokumente.'.$form)
            @else
                <div class="alert alert-warning">
                    <p> There is no form defined</p>      
                </div>
            @endif
            @if( view()->exists('dokumente.'.$form) )
                <div class="clearfix"></div>
                <div class="col-xs-12 form-buttons">
                    <button class="btn btn-white" type="reset">{{ trans('formWrapper.reset') }}</button>
                    <button class="btn btn-primary" type="submit">submitt</button>
                    @yield('buttons')
                </div>
                <br/>
            @endif
    </form>
    <div class="clearfix"></div>
      
    @stop
   