{{-- DOKUMENTE RUNDSCHREIBEN --}}

@extends('master')

@section('page-title')
    Rundschreiben - Übersicht
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper">
            
            <h2 class="title">Meine Rundschreiben</h2>
            
            <div class="box">
                <div class="tree-view" data-selector="rundschreibenMeine">
                    <div class="rundschreibenMeine hide">
                        {{ $rundschreibenMeineTree }}
                    </div>
                </div>
            </div>
             <div class="text-center">
                    {!! $rundschreibenMeine->render() !!}
            </div>
            <!--<div class="text-center">-->
            <!--    <ul class="pagination">-->
            <!--        <li class="pull-left"><a href="#" aria-label="Prev"><span aria-hidden="true">&lt; zurück</span></a></li>-->
            <!--        <li class="active"><a href="#">1</a></li>-->
            <!--        <li><a href="#">2</a></li>-->
            <!--        <li><a href="#">3</a></li>-->
            <!--        <li><a href="#">4</a></li>-->
            <!--        <li class="pull-right"><a href="#" aria-label="Next"><span aria-hidden="true">weiter &gt;</span></a></li>-->
            <!--    </ul>-->
            <!--</div>-->
            
        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

<div class="col-xs-12 box-wrapper">
    <div class="box">
        <div class="row">
            <form action="" method="GET">
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
           </form>
        </div>
    </div>
</div>

<div class="clearfix"></div> <br>

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper">
            
            <h2 class="title">Alle Rundschreiben</h2>
            
            <div class="box">
                <div class="tree-view" data-selector="rundschreibenMeine">
                    <div class="rundschreibenMeine hide">
                        {{ $rundschreibenAllTree }}
                    </div>
                </div>
            </div>
             <div class="text-center">
                    {!! $rundschreibenAll->render() !!}
            </div>
            <!--<div class="text-center">-->
            <!--    <ul class="pagination">-->
            <!--        <li class="pull-left"><a href="#" aria-label="Prev"><span aria-hidden="true">&lt; zurück</span></a></li>-->
            <!--        <li class="active"><a href="#">1</a></li>-->
            <!--        <li><a href="#">2</a></li>-->
            <!--        <li><a href="#">3</a></li>-->
            <!--        <li><a href="#">4</a></li>-->
            <!--        <li class="pull-right"><a href="#" aria-label="Next"><span aria-hidden="true">weiter &gt;</span></a></li>-->
            <!--    </ul>-->
            <!--</div>-->
            
        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop
