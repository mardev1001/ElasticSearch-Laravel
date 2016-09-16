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
            
            <div class="box">
                
                
                @if(count($isoCategories))
                    <!-- <div class="content-nav navbar-collapse" id="nav-collapse">-->
                    <!--    @if(!empty($isoCategories))-->
                    <!--        <ul class="nav first-level">-->
                    <!--            @foreach($isoCategories as $isoCategory)-->
                    <!--                @if($isoCategory->parent)-->
                    <!--                <li>-->
                    <!--                    <a href="{{ url('iso-dokumente/'. $isoCategory->slug) }}">{{ $isoCategory->name }}<span class="fa arrow"></span></a>-->
                    <!--                    <ul class="nav second-level">-->
                    <!--                    @foreach($isoCategories as $isoCategoryChild)-->
                    <!--                        @if($isoCategoryChild->iso_category_parent_id == $isoCategory->id)-->
                    <!--                            <li><a href="{{ url('iso-dokumente/'. $isoCategoryChild->slug ) }}">{{$isoCategoryChild->name}}</a></li>-->
                    <!--                        @endif-->
                    <!--                    @endforeach-->
                    <!--                    </ul>-->
                    <!--                </li>-->
                    <!--                @endif-->
                    <!--            @endforeach-->
                    <!--        </ul>-->
                    <!--    @endif-->
                             
                    <!--</div>-->
                    
                    
                    <ul class="level-1">
                        @foreach($isoCategories as $isoCategory)
                            @if($isoCategory->parent)
                            <li>
                                <a href="{{ url('iso-dokumente/'. $isoCategory->slug) }}">{{ $isoCategory->name }}<span class="fa arrow"></span></a>
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
