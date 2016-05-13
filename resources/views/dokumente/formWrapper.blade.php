@extends('master')
    @section('content') 
    <h1 class="text-primary">
        
    </h1>
    {!! Form::open([
           'url' => '',
           'method' => 'POST',
           'class' => 'horizontal-form']) !!}
           
            @if( view()->exists('dokumente.'.$form) )
                @include('dokumente.'.$form)
            @else
                <div class="alert alert-warning">
                    <p> There is no form defined</p>      
                </div>
            @endif
           
            <div class="clearfix"></div>
            <div class="col-xs-12 form-buttons">
                <button class="btn btn-white" type="reset">{{ trans('formWrapper.reset') }}</button>
                <button class="btn btn-primary" type="submit">submitt</button>
            </div>
            <br/>
    </form>
    <div class="clearfix"></div>
      
    @stop
   