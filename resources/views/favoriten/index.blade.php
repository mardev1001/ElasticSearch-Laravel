{{-- FAVORITEN --}}

@extends('master')
@section('page-title') {{ trans('controller.favorites') }} @stop

@section('content')

@if(count($favoritesAll))

    @foreach($favoritesAll as $favorites)
        
        @if(count($favorites['favoritesPaginated']))
            <div class="row">
                <div class="col-xs-12">
                    <div class="box-wrapper {{ 'favorites-box-' . $favorites['document_type_id'] }}">
                        <h4 class="title">{{ $favorites['document_type_name'] }}</h4>
                        
                        {{--
                        <!-- Old TreeView Code ... Uncomment if required -->
                        <div class="box box-treeview">
                            <div class="tree-view" data-selector="{{ 'favorites-' . $favorites['document_type_id'] }}">
                                <div class="{{ 'favorites-' . $favorites['document_type_id'] }} hide">{{ $favorites['favoritesTreeview'] }}</div>
                            </div>
                        </div> 
                        --}}
                        
                        <div class="box box-linklist">
                              <div class="box box-treeview">
                            <div class="tree-view" data-selector="{{ 'favorites-' . $favorites['document_type_id'] }}">
                                <div class="{{ 'favorites-' . $favorites['document_type_id'] }} hide">{{ $favorites['favoritesTreeview'] }}</div>
                            </div>
                        </div> 
                                {{--<div class="favorite favorite-{{$favorite->id}}">
                                    <a class="pull-left padding-top" href="{{url('dokumente/' . $favorite->id. '/favorit')}}" ><span class="icon-trash display-block"></span></a>
                                    <a class="display-block" href="{{route('dokumente.show', $favorite->published->url_unique)}}">
                                        <span class="item-text">&nbsp; {{ $favorite->date_published }}</span> <br> 
                                        <span class="item-text text-bold">&nbsp; {{ $favorite->name }}</span>
                                    </a>
                                    <div class="clearfix"></div>
                                </div> --}}
                        </div>
                        
                        <div class="text-center box box-pagination">
                            {!! $favorites['favoritesPaginated']->render() !!}
                        </div>
                        
                    </div>
                </div>
            </div>
        @endif

    @endforeach

@endif
    
@stop