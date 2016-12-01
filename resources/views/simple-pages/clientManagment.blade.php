{{-- Client managment--}}

@extends('master')

@section('page-title')
    {{ ucfirst(trans('navigation.mandantenverwaltung')) }}
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper box-white">
            
            <h2 class="title">{{ ucfirst(trans('navigation.mandantenverwaltung')) }}</h2>
            
            <div class="box iso-category-overview">
                
                <ul class="level-1">
                    @if( ViewHelper::universalHasPermission( array(6) ) == true ) 
                        <li>
                            <a href="{{ url('mandanten') }}">{{ ucfirst(trans('navigation.ubersicht')) }}</a>
                        </li>
                    @endif
                        
                    @if( ViewHelper::universalHasPermission( array(6,18,19,20) ) == true ) 
                        <li>
                            <a href="{{ url('mandanten/export') }}">Mandaten Export</a>
                        </li>
                    @endif
                    
                    @if( ViewHelper::universalHasPermission( array(6) ) == true )
                        <li>
                            <a href="{{ url('mandanten/create') }}">{{ ucfirst( trans('navigation.mandanten') ) }} {{ trans('navigation.anlegen') }}</a>
                        </li>
                    @endif
                    
                    @if( ViewHelper::universalHasPermission( array(2,4,17,18) ) == true )
                        <li>
                            <a href="{{ url('benutzer/create') }}">{{ ucfirst( trans('navigation.benutzer') ) }} {{ trans('navigation.anlegen') }}</a>
                        </li>
                    @endif
                </ul>
               
                
            </div>

        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop
@section('afterScript')
            <!--patch for checking iso category document-->
          
                <script type="text/javascript">
                        var detectHref =window.location.href ;
                         setTimeout(function(){
                             if( $('a[href$="'+detectHref+'"]').parent("li").find('ul').length){
                                //  console.log('yeah');
                                  $('a[href$="'+detectHref+'"]').parent("li").find('ul').addClass('in');
                             }
                            
                         },1000 );
                </script>
                    <!-- End variable for expanding document sidebar-->
        @stop