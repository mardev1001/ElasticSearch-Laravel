@extends('master')

@section('page-title-class') {{ ucfirst( trans('navigation.tipsAndTricks') ) }} @stop

@section('page-title') {{ ucfirst( trans('navigation.tipsAndTricks') ) }} @stop

@section('bodyClass') contactPage @stop

@section('content')
<div class="row">
    
    <div class="col-xs-12 col-md-6 ">
        <div class="col-xs-12 box-wrapper">
            <h1 class="title">{{ ucfirst( trans('navigation.tipsAndTricks') ) }}</h1>
             {!! Form::open([
                'method'=>'POST' 
                ]) !!}
            <div class="box box-white">
               
                
            </div><!--end .box-->
            </form>
        </div>
    </div>
    

</div>

@stop
   