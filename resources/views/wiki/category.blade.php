{{-- TELEFONLISTE --}}

@extends('master')

@section('page-title')Kategorie: {{$category->name}} @stop

@section('content')
<div class="row">
    <div class="col-xs-12 col-md-6 box-wrapper">
        <h1 class="title">@lang('wiki.search') Wiki</h1>
        <div class="box box-white">
            <div class="row">
                {!! Form::open(['action' => 'WikiCategoryController@search', 'method'=>'POST']) !!}
                    <input type="hidden" name="category" value="{{$category->id}}" />
                    <div class="input-group">
                        <div class="col-md-12 col-lg-12">
                            {!! ViewHelper::setInput('search', '',old('search'), trans('navigation.wikiSearchPlaceholder'), trans('navigation.wikiSearchPlaceholder'), true) !!}
                            
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <span class="custom-input-group-btn">
                                <button type="submit" class="btn btn-primary no-margin-bottom">
                                    {{ trans('navigation.search') }} 
                                </button>
                            </span>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div><!-- end box -->
    </div><!-- end box wrapper-->
    
    
    @if( count($categoryEntries) )
    <!-- top categorie box-->
    <div class="col-xs-12 col-md-6 ">
            <div class="col-xs-12 box-wrapper home">
                <h1 class="title">Neue Wiki-Einträge</h1>
                <div class="box home box-white">
                    <div class="tree-view hide-icons wiki" data-selector="wikiEntries">
                        <div class="wikiEntries hide">
                            {{ $categoryEntriesTree }}
                        </div>
                    </div>
                </div>
                <!-- pagination box -->
                <div class="text-ceter">
                    {!! $categoryEntries->render() !!}
                </div><!-- end pagination box -->
            </div>
        </div><!--end  top categorie box wrapper-->
        <div class="clearfix"></div>
    @endif
    
        <!-- top categorie box-->
        <div class="col-xs-12 col-md-6 box-wrapper">
            <!--<div class="row">-->
                <div class=" home">
                    <h1 class="title">Suchergebnisse</h1>
                    <div class="box home box-white">
                        @if( count($search) )
                        
                        <div class="tree-view hide-icons wiki" data-selector="wikiEntries">
                            <div class="wikiEntries hide">
                                {{ $searchTreeView }}
                            </div>
                        </div>
                    </div>
                    <!-- pagination box -->
                    <div class="text-ceter">
                        {!! $search->render() !!}
                    </div><!-- end pagination box -->
                    @else
                    <div class="box home box-white">
                        Es wurde kein passender Eintrag gefunden
                    </div>
                    @endif
                    </div>
                </div><!--end  top categorie box wrapper-->
</div><!-- end main row-->




@stop
