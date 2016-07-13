{{-- ISO CATEGORIEN INDEX --}}

@extends('master')

@section('page-title')
    ISO Dokumente - Übersicht
@stop


@section('content')

<div class="row">
    
    <div class="col-xs-12">
        <div class="col-xs-12 box-wrapper">
            
            <h2 class="title">Alle Kategorien</h2>
            
            <div class="box">
                
                <ul>
                    
                    <li class="first-level">
                        
                        <a href="#">Test 1</a>
                        
                        <ul>
                            <li class="second-level">
                                <a href="#">Test 2</a>
                            </li>
                            <li class="second-level">
                                <a href="#">Test 2</a>
                            </li>
                            <li class="second-level">
                                <a href="#">Test 2</a>
                            </li>
                        </ul>
                    </li>
                    
                </ul>
                
            </div>

        </div>
    </div>
    
</div>

<div class="clearfix"></div> <br>

@stop
