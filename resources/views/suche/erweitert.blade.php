{{-- ERWEITERTE SUCHE --}}

@extends('master')

@section('content')

<div class="row">
    <div class="col-xs-12 col-md-12 ">
        <div class="fixed-row">
            <div class="fixed-position ">
                <h1 class="page-title">
                    {{ trans('sucheForm.extended') }} {{ trans('sucheForm.search') }}
                </h1>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>


<fieldset class="form-group">

    <h4>{{ trans('sucheForm.options') }}</h4>
    
    {!! Form::open(['action' => 'SearchController@searchAdvanced', 'method'=>'GET']) !!}
    
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! ViewHelper::setInput('name', '', old('name'), trans('sucheForm.name'), trans('sucheForm.name'), false) !!} 
                </div>
            </div>
        
            <div class="col-lg-4">
                <div class="form-group">
                    {!! ViewHelper::setInput('description', '', old('description'), trans('sucheForm.description'), trans('sucheForm.description'), false) !!} 
                </div>
            </div>
        
            <div class="col-lg-4">
                <div class="form-group">
                    {!! ViewHelper::setInput('betreff', '', old('betreff'), trans('sucheForm.subject'), trans('sucheForm.subject'), false) !!} 
                </div>
            </div>
          
        </div>
            
        <div class="row">
            
            <div class="col-lg-4">
                <div class="form-group">
                    {!! ViewHelper::setInput('inhalt', '', old('inhalt'), trans('sucheForm.content'), trans('sucheForm.content'), false) !!} 
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="form-group">
                    {!! ViewHelper::setInput('tags', '', old('tags'), trans('sucheForm.tags'), trans('sucheForm.tags'), false) !!} 
                </div>
            </div>
            
            <div class="col-lg-2">
                <div class="form-group">
                    {!! ViewHelper::setInput('date_from', '', old('date_from'), trans('sucheForm.date_from'), trans('sucheForm.date_from'), false, '', ['datetimepicker']) !!} 
                </div>
            </div>
            
            <div class="col-lg-2">
                <div class="form-group">
                    {!! ViewHelper::setInput('date_to', '', old('date_to'), trans('sucheForm.date_to'), trans('sucheForm.date_to'), false, '', ['datetimepicker']) !!} 
                </div>
            </div>
          
        </div>
            
    
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    {!! ViewHelper::setSelect(null, 'document_type', '', old('document_type'), trans('sucheForm.document-type'), trans('sucheForm.document-type'), false) !!}
                </div>
            </div>
            
            <div class="col-lg-2 col-sm-6">
                <label class="hidden-xs hidden-sm">&nbsp;</label><br class="hidden-xs hidden-sm">   
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="wiki">
                        {{ trans('sucheForm.wiki') }} {{ trans('sucheForm.entries') }}
                    </label>
                </div>
            </div>
            
            <div class="col-lg-2 col-sm-6">
                <label class="hidden-xs hidden-sm">&nbsp;</label><br class="hidden-xs hidden-sm">   
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="history">
                        {{ trans('sucheForm.history') }} {{ trans('sucheForm.archive') }}
                    </label>
                </div>
            </div>
            
            <div class="col-lg-4">
                <label>&nbsp;</label><br>   
                <button class="btn btn-primary">{{ trans('sucheForm.search') }} </button>
            </div>
        </div>
            
        
        
    {!! Form::close() !!}
        
    
</fieldset>

<div class="search-results">
    
    <h4>{{ trans('sucheForm.search-results') }}: 5 Ergebnisse gefunden</h4> <br>
    
    <div class="results">
        @for($i=1; $i <= 5; $i++)
            <div class="row">
                <div class="col-xs-12">
                    #{{$i}} Runschreiben QMR: QMR 213 - Mindestlohn Änderungen  - 06.04.2016 
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumyeirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua...  <a href="#">mehr</a>
                </div>
                <div class="clearfix"></div><br>
            </div>
        @endfor
    </div>
    
</div>


<div class="clearfix"></div> <br>

@stop



    

    
    
