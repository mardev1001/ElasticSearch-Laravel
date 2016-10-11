{{-- ISO CATEGORIEN INDEX --}}

@extends('master')

@section('page-title')
    ISO Dokumente - Übersicht
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper">
            
            <h2 class="title">Alle Kategorien</h2>
            
            <div class="box iso-category-overview">
                
                @if(count($isoCategories))
                    
                    <ul class="level-1">
                        @foreach($isoCategories as $isoCategory)
                            @if($isoCategory->parent)
                            <li>
                                <a href="{{ url('iso-dokumente/'. $isoCategory->slug) }}">{{ $isoCategory->name }}</a>
                                <ul class="level-2">
                                @foreach($isoCategories as $isoCategoryChild)
                                    @if($isoCategoryChild->iso_category_parent_id == $isoCategory->id)
                                        <li><a href="{{ url('iso-dokumente/'. $isoCategoryChild->slug ) }}">{{$isoCategoryChild->name}}</a></li>
                                    @endif
                                @endforeach
                                </ul>
                            </li>
                            @endif
                        @endforeach
                    </ul>
                @else
                    Keine Einträge gefunden.
                @endif
                
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