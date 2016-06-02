@extends('master')
    @section('content') 
   <div class="row">
        <div class="col-xs-12 col-md-12 white-bgrnd">
            <div class="fixed-row">
                <div class="fixed-position ">
                    <h1 class="page-title">
                        Title
                    </h1>
                </div>
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div>
    

    <div class="col-xs-12 box-wrapper">
        <div class="box">
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
                                <button class="btn btn-primary" type="submit" name="attachment" value="attachment"> 
                                    <span class="fa fa-file-text-o"></span>
                                    Anhange
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
        </div>
    </div>

    <div class="clearfix"></div>
      
    @stop
   