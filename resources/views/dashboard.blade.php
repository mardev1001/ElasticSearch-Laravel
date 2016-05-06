@extends('master')

@section('content')

    <h1 class="text-primary">
        Dashboard
    </h1>

    <div class="row">
        <div class="col-xs-12 col-md-6 ">
            <div class="col-xs-12 bordered home">
                <h4 class="text-info">Neue Dokumente/Rundschreiben</h3>
                <div class="box home">
                    <div class="tree-view" data-selector="rundschreibenNeu">
                        <div class="rundschreibenNeu hide">
                            {{ $rundschreibenNeu }}
                        </div>
                    </div>
                </div>
                
                <div class="text-center">
                    <ul class="pagination">
                        <li><a href="#" aria-label="Next"><span aria-hidden="true">&laquo;</span></a></li>
                        <li class="active"><a href="#">1</a></li>
                        @for($i=2; $i < 5; $i++)
                            <li><a href="#">{{$i}}</a></li>
                        @endfor
                        <li><a href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                    </ul>
                </div>
                
            </div>
        </div>
    
        <div class="col-xs-12 col-md-6 ">
            <div class="col-xs-12 bordered home">
                <h4 class="text-info">Ersteller - Meine Rundschreiben</h3>
                <div class="box home">
                    <div class="tree-view" data-selector="rundschreibenMeine">
                        <div class="rundschreibenMeine hide">
                            {{ $rundschreibenMeine }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    <br>

    <div class="row">
        <div class="col-xs-12 col-md-6 ">
            <div class="col-xs-12 bordered home">
                <h4 class="text-info">Neue Wiki-Einträge</h3>
                <div class="box home">
                    <div class="tree-view" data-selector="wikiEntries">
                        <div class="wikiEntries hide">
                            {{ $wikiEntries }}
                        </div>
                    </div>
                </div>
                <div class="buttons wiki">
                    <a href="#" class="btn btn-default">Zum WIKI</a>
                    <div class="clearfix"></div><br>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-6 ">
            <div class="col-xs-12 bordered home">
                <h4 class="text-info">Ersteller - Meine Dokumente</h3>
                <div class="box home">
                    <div class="tree-view" data-selector="documentsNew">
                        <div class="documentsNew hide">
                            {{ $documentsNew }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="clearfix"></div><br>
    
    
    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-12 bordered">
                <h4 class="text-info">Neue Kommtare</h3>
                <div class="tree-view" data-selector="commentsNew">
                    <div class="commentsNew hide">
                        {{ $commentsNew }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div><br>

    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-12 bordered">
                <h4 class="text-info">Meine letzten Kommentare</h3>
                <div class="tree-view" data-selector="commentsMy">
                    <div class="commentsMy hide">
                        {{ $commentsMy }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="clearfix"></div><br>

@stop
   