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
                <div class="row">
                    <div class="col-xs-12 form-buttons">
                        @if( isset($backButton) )
                            <a href="{{$backButton}}" class="btn btn-info"><span class="fa fa-chevron-left"></span> Zurück</a>
                        @endif
                        <button class="btn btn-primary" type="submit" name="save" value="save">
                            <span class="fa fa-floppy-o"></span> Speichern
                        </button>
                        <button class="btn btn-primary" type="submit" name="next" value="next"> 
                            <span class="fa fa-chevron-right"></span>
                            Weiter
                        </button>    
                        
                        @yield('buttons')
                    </div>
                </div>
                <br/>
            @endif
    </form>
    <div class="clearfix"></div>
      
    @stop
   