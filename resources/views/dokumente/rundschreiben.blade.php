{{-- DOKUMENTE RUNDSCHREIBEN --}}

@extends('master')

@section('content')

<h1 class="text-primary">Rundschreiben</h1>

<div class="clearfix"></div> <br>

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 bordered">
            
            <h4 class="text-info">Meine Rundschreiben</h3>
            
            <div class="box home">
                <div class="tree-view" data-selector="rundschreibenMeine">
                    <div class="rundschreibenMeine hide">
                        {{ $rundschreibenMeine }}
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
            
        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

<div class="row">
    <div class="col-xs-12 col-md-6">
        <form action="" method="GET">
            <div class="col-lg-6">
                <div class="input-group">
                    {!! ViewHelper::setInput('search', '', old('search'), trans('navigation.search'), trans('navigation.search'), true) !!}
                    <span class="input-group-btn">
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
        <div class="col-xs-12 bordered">
            
            <h4 class="text-info">Alle Rundschreiben</h3>
            
            <div class="box home">
                <div class="tree-view" data-selector="rundschreibenMeine">
                    <div class="rundschreibenMeine hide">
                        {{ $rundschreibenAll }}
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
            
        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop
