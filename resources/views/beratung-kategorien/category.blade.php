{{-- ISO DOKUMENTE --}}

@extends('master')

@section('page-title')
    Dokumente - @lang('navigation.juristenPortalBeratung') 
    @if($category->juristenBeratungParent)- {{$category->juristenBeratungParent->name}}@endif
    @if($category)- {{$category->name}}@endif
@stop
@section('content')

<div class="clearfix"></div> 
   
        <div class="row">
           <div class="col-xs-12 col-md-6">
                    <div class="box-wrapper box-white">
                        <h2 class="title">Dokumente</h2>
                        @if(count($documents))
                            <div class="box scrollable">
                                <div class="tree-view" data-selector="isoEntwurfTree">
                                    <div class="isoEntwurfTree hide">
                                        {{ $documentsTree }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-center ">
                                {!! $documents->render() !!}
                            </div>
                        @else
                            <div class="box">
                                <span class="text">Keine Dokumente gefunden.</span>
                            </div>
                        @endif
                    </div>
                </div>
            
            
        </div>
        
  
    <!--</div>-->
    
    
<div class="clearfix"></div> <br>

@stop