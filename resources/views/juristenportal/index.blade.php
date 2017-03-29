{{-- Client managment--}}

@extends('master')

@section('page-title')
    {{ ucfirst( trans('navigation.juristenPortal') )}}
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper box-white">
            
            <h2 class="title">{{ ucfirst( trans('juristenPortal.juristenportal') )}}</h2>
            
            <div class="box iso-category-overview">
                
                <ul class="level-1">
                    <li>
                        <a href="{{ url('#') }}">{{ ucfirst( trans('juristenPortal.documents') ) }} </a>
                    </li>
                    <li>
                        <a href="{{ url('juristenportal/upload') }}">{{ ucfirst( trans('juristenPortal.upload') ) }} </a>
                    </li>
                    <li>
                        <a href="{{ url('#') }}">{{ ucfirst( trans('juristenPortal.calendar') ) }} </a>
                    </li>
                    <li>
                        <a href="{{ url('#') }}">{{ ucfirst( trans('juristenPortal.files') ) }} </a>
                    </li>
                    
                        <li>
                            <a href="{{url('juristenportal-kategorien/alle')}}"> @lang('navigation.juristenPortalCategories')
                                @if( count($juristenCategories) ) <span class="fa arrow"></span> @endif
                            </a>
                        
                        @if(!empty($juristenCategories))
                            @foreach( $juristenCategories as $juristenCategory)
                            <ul class="level-2">
                               <li>
                                    <a href="{{url('juristenportal-kategorien/'.$juristenCategory->id)}}">{{ $juristenCategory->name }}
                                        @if( count($juristenCategory->juristCategoriesActive) ) <span class="fa arrow"></span> @endif
                                    </a>
                                        @if( count($juristenCategory->juristCategoriesActive) )
                                        <ul class="level-2">
                                            @foreach( $juristenCategory->juristCategoriesActive as $subLevel1)
                                            <li>
                                                <a href="{{url('juristenportal-kategorien/'.$subLevel1->id)}}">{{ $subLevel1->name }}
                                                    @if( count($subLevel1->juristCategoriesActive) ) <span class="fa arrow"></span> @endif
                                                </a>
                                                @if( count( $subLevel1->juristCategoriesActive ) )
                                                <ul class="level-3">
                                                    @foreach( $subLevel1->juristCategoriesActive as $subLevel2)
                                                        <li>
                                                            <a href="{{url('juristenportal-kategorien/'.$subLevel2->id)}}">{{ $subLevel2->name }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </li>
                                            @endforeach
                                        </ul>
                                        @endif
                                  
                                </li>
                            </ul>
                            @endforeach
                        @endif
                    </li>
                    <li>
                        <a href="{{ url('#') }}">{{ ucfirst( trans('juristenPortal.createFile') ) }} </a>
                    </li>
                </ul>
                
            </div>

        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop