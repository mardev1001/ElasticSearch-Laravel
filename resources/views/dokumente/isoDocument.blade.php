{{-- ISO DOKUMENTE --}}

@extends('master')

@section('content')

<div class="row">
    <div class="col-xs-12 col-md-12 white-bgrnd">
        <div class="fixed-row">
            <div class="fixed-position ">
                <h1 class="page-title">
                    ISO Dokumente
                </h1>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<div class="clearfix"></div> <br>

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper">
            
            <h2 class="title">Meine ISO Dokumente</h2>
            
            @if(count($isoDocumentsBySlug))
            
            <div class="box home">
                <div class="tree-view" data-selector="rundschreibenMeine">
                    <div class="rundschreibenMeine hide">
                        {{ $isoDocumentsBySlug }}
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <ul class="pagination">
                    <li><a href="#" aria-label="Next"><span aria-hidden="true">«</span></a></li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                </ul>
            </div>
            
            @else
            
            <span class="text">Keine Dokumente gefunden.</span>
            
            @endif
            
        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

<div class="col-xs-12 box-wrapper">
    <div class="row">
        <form action="" method="GET">
            <div class="input-group">
                <div class="col-lg-12">
                    {!! ViewHelper::setInput('search', '', old('search'), trans('navigation.search'), trans('navigation.search'), true) !!}
                </div>
                <div class="col-lg-12">
                    <span class="custom-input-group-btn">
                        <button type="submit" class="btn btn-primary">
                            <span class="fa fa-search"></span> {{ trans('navigation.search') }} 
                        </button>
                    </span>
                </div>
            </div>
       </form>
    </div>
</div>

<div class="clearfix"></div> <br>

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper">
            
            <h2 class="title">Alle ISO Dokumente</h2>
            
            @if(count($isoDocumentsAll))
            
            <div class="box">
                <div class="tree-view" data-selector="rundschreibenMeine">
                    <div class="rundschreibenMeine hide">
                        {{ $isoDocumentsAll }}
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <ul class="pagination">
                    <li><a href="#" aria-label="Next"><span aria-hidden="true">«</span></a></li>
                    <li class="active"><a href="#">1</a></li>
                    <li><a href="#">2</a></li>
                    <li><a href="#">3</a></li>
                    <li><a href="#">4</a></li>
                    <li><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                </ul>
            </div>
            
            @else
            
            <span>Keine Dokumente gefunden.</span>
            
            @endif
            
        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop
