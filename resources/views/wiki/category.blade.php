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
    <div class="col-xs-12 box-wrapper">
        <div class="box">
            <div class="row">
                <div class="col-md-12">
                    @foreach( $categoryEntries as $k=>$entry )
                        <!--link box-->
                        <div class="col-md-3">
                            <a href="{{ url('wiki/'.$entry->id) }}" class="text-center">
                                <h4 >{{$entry->name}} </h4>
                            </a>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! ViewHelper::sentencesToDisplay($entry->content,6) !!}
                                </div>
                            </div>
                            <br/>
                              <small>{{ $entry->user->first_name }} {{ $entry->user->last_name }} - {{ $entry->created_at }} </small>
                        </div><!--end link box-->
                        @if( $k+1%4 == 0 )
                            <div class="clearfix"></div>
                        @endif
                    @endforeach
                </div><!-- end col-md-12-->
            </div><!-- end row-->
            
            <!-- pagination box -->
            <div class="text-ceter">
                {!! $categoryEntries->render() !!}
            </div><!-- end pagination box -->
        
        </div><!-- end box -->
    </div><!--end  top categorie box wrapper-->
    @endif
    
</div><!-- end main row-->




@stop
