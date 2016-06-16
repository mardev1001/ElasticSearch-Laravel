{{-- ERWEITERTE SUCHE --}}

@extends('master')

@section('page-title') {{ trans('sucheForm.extended') }} {{ trans('sucheForm.search') }} @stop

@section('content')


<fieldset class="form-group">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('sucheForm.options') }}</h4>
        
        {!! Form::open(['action' => 'SearchController@searchAdvanced', 'method'=>'GET']) !!}
            <div class="box">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            {!! ViewHelper::setInput('name', '', old('name'), trans('sucheForm.name'), trans('sucheForm.name'), false) !!} 
                        </div>
                    </div>
                
                    <div class="col-lg-4">
                        <div class="form-group">
                            {!! ViewHelper::setInput('beschreibung', '', old('beschreibung'), trans('sucheForm.description'), trans('sucheForm.description'), false) !!} 
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
                            {!! ViewHelper::setInput('datum_von', '', old('datum_von'), trans('sucheForm.date_from'), trans('sucheForm.date_from'), false, '', ['datetimepicker']) !!} 
                        </div>
                    </div>
                    
                    <div class="col-lg-2">
                        <div class="form-group">
                            {!! ViewHelper::setInput('datum_bis', '', old('datum_bis'), trans('sucheForm.date_to'), trans('sucheForm.date_to'), false, '', ['datetimepicker']) !!} 
                        </div>
                    </div>
                  
                </div>
                    
            
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            {!! ViewHelper::setSelect($documentTypes, 'document_type', '', old('document_type'), trans('sucheForm.document-type'), trans('sucheForm.document-type'), false) !!}
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-sm-6">
                        <!--<br class="hidden-xs hidden-sm">   -->
                        <div class="checkbox">
                            <input type="checkbox" name="wiki" id="wiki">
                            <label for="wiki"> {{ trans('sucheForm.wiki') }} <br class="hidden-lg"> {{ trans('sucheForm.entries') }} </label>
                        </div>
                    </div>
                    
                    <div class="col-lg-2 col-sm-6">
                        <!--<br class="hidden-xs hidden-sm">   -->
                        <div class="checkbox">
                            <input type="checkbox" name="history" id="history">
                            <label for="history"> {{ trans('sucheForm.history') }} <br class="hidden-lg"> {{ trans('sucheForm.archive') }} </label>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <label>&nbsp;</label><br>   
                        <button class="btn btn-primary">{{ trans('sucheForm.search') }} </button>
                    </div>
                </div>
               
            </div>
            
        {!! Form::close() !!}
    
    </div>    
    
</fieldset>

<div class="search-results">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('sucheForm.search-results') }}: <span class="text"> {{count($results)}} Ergebnisse gefunden</span></h4> <br>
        <div class="box">
            
            @if(count($results))
                @foreach($results as $key=>$document)
                    <div class="row">
                        <div class="col-xs-12 text result">
                            <div class="healine"> 
                                <a href="{{route('dokumente.show', $document)}}" class="link">
                                    <strong>#{{$key+1}} {{$document->documentType->name}} - {!! ViewHelper::highlightKeyword($parameter, $document->name) !!} - {!! ViewHelper::highlightKeyword($parameter, $document->betreff) !!} - {{ $document->date_published }} </strong> 
                                </a>
                            </div>
                            <div class="document-text"> 
                                <strong class="summary">{!! ViewHelper::highlightKeyword($parameter, $document->summary) !!}</strong>
                                <div class="content">
                                    @if(count($variants))
                                        @foreach($variants as $variant)
                                            @if($document->id == $variant->document_id)
                                                <div class="document-variant">
                                                    <span class="number">Variante {{ $variant->variant_number }}:</span>
                                                    {!! ViewHelper::highlightKeyword($parameter, ViewHelper::extractText($parameter, $variant->inhalt)) !!}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                @endforeach
            @else
                <div class="row">
                    <div class="col-xs-12 text result">
                        <div class="healine">
                            Keine suchergebnisse gefunden.
                        </div>
                    </div>
                </div>
            @endif
        
        </div>
    </div>
</div>

<div class="clearfix"></div> <br>

@stop



    

    
    
