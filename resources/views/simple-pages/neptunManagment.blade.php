{{-- Client managment--}}

@extends('master')

@section('page-title')
    NEPTUN-{{ ucfirst( trans('navigation.verwaltung') )}}
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper box-white">
            
            <h2 class="title">NEPTUN-{{ ucfirst( trans('navigation.verwaltung') )}}</h2>
            
            <div class="box iso-category-overview">
                
                <ul class="level-1">
                    @if( ViewHelper::universalHasPermission( array(6) ) == true )
                        <li>
                            <a href="{{ url('benutzer/standard-benutzer') }}">{{ ucfirst( trans('contactForm.defaultUser') ) }}</a>
                        </li>
                    @endif
                        
                    @if( ViewHelper::universalHasPermission( array(6) ) == true )    
                        <li>
                            <a href="{{ url('kontaktanfragen') }}">{{ ucfirst( trans('contactForm.kontaktanfragen') ) }}</a>
                        </li>
                    @endif
                    
                    @if( ViewHelper::universalHasPermission( array(6) ) == true )
                        <li>
                            <a href="{{ url('empfangerkreis') }}">{{ ucfirst( trans('navigation.adressate') ) }}</a>
                        </li>
                    @endif
                    
                    @if( ViewHelper::universalHasPermission( array(6) ) == true )
                        <li>
                            <a href="{{ url('dokument-typen') }}">{{ ucfirst( trans('navigation.document') ) }}-{{ ucfirst( trans('navigation.types') ) }}</a>
                        </li>
                    @endif
                    
                    @if( ViewHelper::universalHasPermission( array(6) ) == true )
                        <li>
                            <a href="{{ url('iso-kategorien') }}">{{ ucfirst( trans('navigation.iso') ) }}-{{ trans('navigation.kategorien') }} </a>
                        </li>
                    @endif
                    
                    @if( ViewHelper::universalHasPermission( array(35), false ) == true ) 
                        <li>
                            <a href="{{ url('juristenportal-kategorien') }}">{{ ( trans('navigation.juristenPortal') ) }}-{{ trans('navigation.kategorien') }} </a>
                        </li>
                    @endif
                    
                    @if( ViewHelper::universalHasPermission( array(6) ) == true )
                        <li>
                            <a href="{{ url('rollen') }}">{{ ucfirst( trans('navigation.rollenverwatung') ) }}</a>
                        </li>
                    @endif
                    
                    @if( ViewHelper::universalHasPermission( array(6) ) == true ) 
                            <li>
                                <a href="{{ url('wiedervorlagen-status') }}">{{ ucfirst( trans('navigation.wiedervorlagenStatus') ) }}</a>
                            </li>
                    @endif
                    
                    @if( ViewHelper::universalHasPermission( array(6) ) == true )
                        <li>
                            <a href="{{ url('tipps-und-tricks/create') }}">{{ ucfirst( trans('navigation.tipsAndTricks') ) }}</a>
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