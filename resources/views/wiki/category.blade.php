{{-- TELEFONLISTE --}}

@extends('master')

@section('page-title') Wiki Kategorien {{$category->name}} @stop

@section('content')
<div class="row">
    <div class="col-xs-12 box-wrapper">
        <div class="box">
            <div class="row">
                {!! Form::open(['action' => 'WikiController@search', 'method'=>'POST']) !!}
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
    
    
    
    @if( $categoryEntries )
    <!-- top categorie box-->
    <div class="col-xs-12 col-md-6 ">
            <div class="col-xs-12 box-wrapper home">
                <h1 class="title">Neue Wiki-Einträge</h1>
                <div class="box home">
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
    @endif
    
</div><!-- end main row-->




@stop