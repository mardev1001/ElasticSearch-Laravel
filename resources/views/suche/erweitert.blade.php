{{-- ERWEITERTE SUCHE --}}

@extends('master')

@section('page-title') {{ trans('sucheForm.extended') }} {{ trans('sucheForm.search') }} @stop

@section('content')

<fieldset class="form-group">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('sucheForm.search-title') }}</h4>
        
        {!! Form::open(['action' => 'SearchController@searchAdvanced', 'method'=>'GET']) !!}
            <div class="box box-white
            
            ">
                
                <div class="row">
                    
                    <div class="col-lg-4">
                        <div class="form-group">
                            @if(isset($parameter))
                                {!! ViewHelper::setInput('parameter', $parameter, $parameter, trans('navigation.searchParameter'), trans('navigation.searchParameter'), false, '', ['adv-parameter']) !!} 
                            @else
                                {!! ViewHelper::setInput('parameter', '', old('parameter'), trans('navigation.searchParameter'), trans('navigation.searchParameter'), false, '', ['adv-parameter']) !!} 
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-lg-2">
                        <div class="checkbox">
                            <input type="checkbox" @if((old('adv-search'))) checked @endif name="adv-search" id="adv-search">
                            <label for="adv-search"> {{ trans('sucheForm.erweiterte-suche') }} </label>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">{{ trans('sucheForm.search') }} </button>
                            {{-- <button type="reset" class="btn btn-primary">{{ trans('sucheForm.reset') }} </button> --}}
                        </div>
                    </div>
                    
                </div>
                
                <div class="advanced-search">
                    
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
                    
                        {{--
                        <div class="col-lg-4">
                            <div class="form-group">
                                {!! ViewHelper::setInput('betreff', '', old('betreff'), trans('sucheForm.subject'), trans('sucheForm.subject'), false) !!} 
                            </div>
                        </div>
                        --}}
                        
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
                            <div class="form-group document-type-select">
                                {{-- ViewHelper::setSelect($documentTypes, 'document_type', '', old('document_type'), trans('sucheForm.document-type'), trans('sucheForm.document-type'), false) --}}
                                <div class="form-group">
                                    <label class="control-label"> {{ trans('sucheForm.document-type') }}</label>
                                    <select name="document_type" class="form-control select" data-placeholder="{{ strtoupper( trans('sucheForm.document-type') ) }}">
                                        <!--<option value=""></option>-->
                                        <option value="">Alle</option>
                                        @foreach($documentTypes as $documentType)
                                            <option value="{{$documentType->id}}" @if(old('document_type') == $documentType->id) selected @endif > 
                                                {{ $documentType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                            
                        <div class="col-md-2 col-lg-2 qmr-select"> 
                            <div class="form-group">
                                {!! ViewHelper::setInput('qmr_number', '', old('qmr_number'), trans('documentForm.qmr') , 
                                       trans('documentForm.qmr'), false, 'number', array(), array() )!!}
                            </div>   
                        </div>
                        
                        <!-- input box-->
                        <div class="col-md-2 col-lg-2 iso-category-select"> 
                            <div class="form-group">
                                {!! ViewHelper::setInput('iso_category_number', '', old('iso_category_number'), trans('documentForm.isoNumber') , 
                                       trans('documentForm.isoNumber') , false, 'number', array(), array() ) !!}
                            </div>   
                        </div>
                        
                        <div class="col-md-2 col-lg-2 additional-letter"> 
                            <div class="form-group">
                                {!! ViewHelper::setInput('additional_letter', '', old('additional_letter'), trans('documentForm.additionalLetter') , 
                                       trans('documentForm.additionalLetter')  ) !!}
                            </div>   
                        </div>
                        
                        <div class="col-md-4 col-lg-4"> 
                            <div class="form-group">
                                <label class="control-label"> {{ trans('documentForm.user') }}</label>
                                <select name="user_id" class="form-control select" data-placeholder="{{ strtoupper( trans('documentForm.user') ) }}">
                                    <!--<option value=""></option>-->
                                    <option value="">Alle</option>
                                    @foreach($mandantUsers as $mandantUser)
                                        <option value="{{$mandantUser->user->id}}" @if(old('user_id') == $mandantUser->user->id) selected @endif > 
                                            {{ $mandantUser->user->first_name }} {{ $mandantUser->user->last_name }} 
                                        </option>
                                    @endforeach
                                </select>
                            </div>   
                        </div>
                    </div>
                    
                    <div class="row">
                        {{--
                        @if( ViewHelper::universalHasPermission( array(15,16) ) == true ) 
                        <div class="col-lg-2 col-sm-6">
                            <!--<br class="hidden-xs hidden-sm">   -->
                            <div class="checkbox">
                                <input type="checkbox" @if((old('wiki'))) checked @endif name="wiki" id="wiki">
                                <label for="wiki"> {{ trans('sucheForm.wiki') }} <br class="hidden-lg"> {{ trans('sucheForm.entries') }} </label>
                            </div>
                        </div>
                        @endif
                        --}}
                        
                        @if( ViewHelper::universalHasPermission( array(14) ) == true ) 
                            <div class="col-lg-2 col-sm-6">
                                <!--<br class="hidden-xs hidden-sm">   -->
                                <div class="checkbox">
                                    <input type="checkbox" @if((old('history'))) checked @endif name="history" id="history">
                                    <label for="history"> {{ trans('sucheForm.archive') }} </label>
                                </div>
                            </div>
                        @endif
                        
                    </div>
               
                </div>
                
            </div>
            
        {!! Form::close() !!}
    
    </div>    
    
</fieldset>

@if(count($results))

<div class="search-results">
    <div class="box-wrapper">
        {{-- <h4 class="title">{{ trans('sucheForm.search-results') }}@if(isset($parameter)) für "{{$parameter}}"@endif: <span class="text"> {{count($results)}} Ergebnisse gefunden</span></h4> --}}
        <h4 class="title">{{ trans('sucheForm.search-results') }}: <span class="text"> {{count($results)}} Treffer</span></h4>
        
        <div class="sort-urls">
            <a href="{{ Request::fullUrl() }}&sort=asc" class="link">{{ trans('sucheForm.publish-date') }} <i class="fa fa-arrow-up" aria-hidden="true"></i></a> / 
            <a href="{{ Request::fullUrl() }}&sort=desc" class="link">{{ trans('sucheForm.publish-date') }} <i class="fa fa-arrow-down" aria-hidden="true"></i></a>
        </div>
        
        <div class="box box-white">
    
            @foreach($results as $key=>$document)
            
            @if(isset($document->published->url_unique))
                <div class="row">
                    <div class="col-xs-12 text result">
                        <div class="healine"> 
                            
                            @if(old('history')) 
                                <a href="{{url('/dokumente/'. $document->id)}}" class="link">
                            @else
                                <a href="{{url('/dokumente/'. $document->published->url_unique)}}" class="link">
                            @endif
                            
                                <strong>
                                #{{$key+1}} 
                                @if(old('name')) 
                                    
                                    @if($document->documentType->id == 3)
                                        {!! "QMR " . $document->qmr_number.$document->additional_letter !!} -
                                    @elseif($document->documentType->id == 4)
                                        {{ $document->documentType->name }} -
                                    @else
                                        {{ $document->documentType->name }} -
                                    @endif
                                    
                                    @if(isset($parameter) && !empty($parameter)) 
                                        {!! ViewHelper::highlightKeywords(array($parameter, old('name')), $document->name_long) !!} -
                                    @else
                                        {!! ViewHelper::highlightKeyword(old('name'), $document->name_long) !!} -
                                    @endif
                                    
                                    {{ \Carbon\Carbon::parse($document->date_published)->format('d.m.Y') }} 
                                @else
                                    @if($document->documentType->id == 3)
                                        QMR {{$document->qmr_number.$document->additional_letter}} - 
                                    @elseif($document->documentType->id == 4)
                                        {{$document->documentType->name}} - 
                                    @else
                                        {{$document->documentType->name}} - 
                                    @endif
                                    
                                    @if(isset($parameter) && !empty($parameter)) 
                                        {!! ViewHelper::highlightKeyword($parameter, $document->name_long) !!} -
                                    @else
                                        {!! $document->name_long !!} - 
                                    @endif
                                    
                                    {{ \Carbon\Carbon::parse($document->date_published)->format('d.m.Y') }} 
                                @endif
                                </strong> 
                            </a>
                        </div>
                        <div class="document-text"> 
                            <div class="tags">
                                @if((stripos($document->search_tags, old('tags')) !== false)  || (stripos($document->search_tags, $parameter)!== false))
                                    <strong>
                                         <span class="highlight">Treffer: Stichwörter</span>
                                    </strong>
                                @endif
                            </div>
                            <div class="summary">
                                <strong>
                                    @if(old('beschreibung')) 
                                        {!! ViewHelper::highlightKeyword(old('beschreibung'), $document->summary) !!}
                                    @else
                                        @if(isset($parameter) && !empty($parameter)) 
                                            {!! ViewHelper::highlightKeyword($parameter, $document->summary) !!}    
                                        @else
                                            {!! $document->summary !!}
                                        @endif
                                    @endif
                                </strong>
                            </div>
                            <div class="content">
                                @foreach(ViewHelper::documentVariantPermission($document)->variants as $variant)
                                    @if(old('inhalt'))
                                        {!! ViewHelper::highlightKeyword(old('inhalt'), ViewHelper::extractText(old('inhalt'), $variant->inhalt)) !!}
                                    @else
                                        @if(isset($parameter) && !empty($parameter))
                                            {!! ViewHelper::highlightKeyword($parameter, ViewHelper::extractText($parameter, $variant->inhalt)) !!}
                                        @else
                                            {!! ViewHelper::extractTextSimple($variant->inhalt) !!}
                                        @endif
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div> <br>
                </div>
            @endif
            
            @endforeach
        
        </div>
    </div>
</div>

@endif

<div class="clearfix"></div> <br>

@if(count($resultsWiki))

<div class="search-results-wiki">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('sucheForm.search-results') }} Wiki: <span class="text"> {{count($resultsWiki)}} Treffer</span></h4> <br>
        <div class="box box-white">

                @foreach($resultsWiki as $key=>$wiki)
                    <div class="row">
                        <div class="col-xs-12 text result">
                            <div class="healine"> 
                                <a href="{{route('wiki.show', $wiki)}}" class="link">
                                    <strong>
                                        #{{$key+1}} {{$wiki->category->name}} - 
                                        
                                        @if(old('name')) 
                                            {!! ViewHelper::highlightKeyword(old('name'), $wiki->name) !!} -
                                        @else
                                            {!! $wiki->name !!} - 
                                        @endif
                                        
                                        {{\Carbon\Carbon::parse($wiki->created_at)->format('d.m.Y H:i:s')}} 
                                    </strong> 
                                </a>
                            </div>
                            <div class="document-text"> 
                                <div class="content">
                                    @if(old('inhalt')) 
                                        {!! ViewHelper::highlightKeyword(old('inhalt'), ViewHelper::extractText(old('inhalt'), $wiki->content)) !!}
                                    @else
                                        {{ $wiki->content }}
                                    @endif
                                </div>
                                            
                                
                            </div>
                        </div>
                        <div class="clearfix"></div> <br>
                    </div>
                @endforeach
           
           
        
        </div>
    </div>
</div>

<div class="clearfix"></div> <br>

@endif

@stop



    

    
    
