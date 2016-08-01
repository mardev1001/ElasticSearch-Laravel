{{-- TELEFONLISTE --}}

@extends('master')

@section('page-title') {{ trans('wiki.wikiStart') }} @stop

@section('content')
<div class="row">
    <div class="col-xs-12 box-wrapper">
        <div class="box">
            <div class="row">
                {!! Form::open(['action' => 'DocumentController@search', 'method'=>'POST']) !!}
                    <div class="input-group">
                        <div class="col-md-12 col-lg-12">
                            {!! ViewHelper::setInput('search', '', old('search'), trans('navigation.search_placeholder'), trans('navigation.search_placeholder'), true) !!}
                            
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
    
    @if( $topCategories )
    <!-- top categorie box-->
    <div class="col-xs-12 box-wrapper">
        <div class="box">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Top Kategorien</h2>
                    @foreach( $topCategories as $cat )
                        <!--link box-->
                        <div class="col-md-2">
                            <a href="{{ url('wiki/kategorie/'.$cat->id) }}"><h4>{{$cat->name}}</h4></a>
                        </div><!--end link box-->
                    @endforeach
                </div><!-- end col-md-12-->
            </div><!-- end row-->
        </div><!-- end box -->
    </div><!--end  top categorie box wrapper-->
    @endif
    
    @if( $newestWikiEntries )
    <!-- top categorie box-->
    <div class="col-xs-12 box-wrapper">
        <div class="box">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="title">Neueste Beiträge (Änderungen)</h2>
                    @foreach( $newestWikiEntries as $entry )
                        <!--link box-->
                        <div class="col-md-12">
                            <a href="{{ url('wiki/'.$entry->id) }}">
                                {{ $entry->user->first_name }} {{ $entry->user->last_name }} - {{ $entry->created_at }} - {{$entry->name}} 
                            </a>
                        </div><!--end link box-->
                    @endforeach
                </div><!-- end col-md-12-->
            </div><!-- end row-->
            
            <!-- pagination box -->
            <div class="text-ceter">
                {!! $newestWikiEntries->render() !!}
            </div><!-- end pagination box -->
        
        </div><!-- end box -->
    </div><!--end  top categorie box wrapper-->
    @endif
    
</div><!-- end main row-->




@stop
