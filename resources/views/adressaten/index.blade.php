{{-- ADRESSATEN --}}

@extends('master')

@section('content') 

<div class="row">
    <div class="col-xs-12 col-md-12 white-bgrnd">
        <div class="fixed-row">
            <div class="fixed-position ">
                <h1 class="page-title">
                    {{ trans('adressatenForm.adressats') }} {{ trans('adressatenForm.management') }}
                </h1>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<fieldset class="form-group">
    <div class="box-wrapper">
        <h4 class="title">{{ trans('adressatenForm.adressat') }} {{ trans('adressatenForm.add') }}</h4>
        
        <div class="row">
            <!-- input box-->
            <div class="col-md-4"> 
                {!! Form::open(['route' => 'adressaten.store']) !!}
                <div class="">
                    <label> {{ trans('adressatenForm.name') }} <i class="fa fa-asterisk text-info"></i></label>
                    <input type="text" class="form-control" name="name" placeholder="{{ trans('adressatenForm.name') }}" required/>
                    <div class="custom-input-group-btn"><button class="btn btn-primary"> {{ trans('adressatenForm.add') }} </button></div>
                    <br>
                </div>
                {!! Form::close() !!}
            </div><!--End input box-->
        </div>
    </div>
</fieldset>


<fieldset class="form-group">
    
    <h4 class="title">{{ trans('adressatenForm.adressats') }} {{ trans('adressatenForm.overview') }}</h4> <br>
     <div class="box-wrapper">    
        <div class="row">
            <div class="col-xs-12 col-lg-8">
                <h4 class="title">{{ trans('adressatenForm.adressat') }}</h4>
              
                @foreach($adressate as $adressat)
                <div class="row">
                    {!! Form::open(['route' => ['adressaten.update', 'adressaten'=> $adressat->id], 'method' => 'PATCH']) !!}
                    <div class="col-xs-12 col-md-8">
                         <input type="text" class="form-control" name="name" value="{{ $adressat->name }}" placeholder="Name"/>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        
                        @if($adressat->active)
                            <button class="btn btn-success" type="submit" name="activate" value="1">{{ trans('adressatenForm.active') }}</button>
                        @else
                            <button class="btn btn-danger" type="submit" name="activate" value="0">{{ trans('adressatenForm.inactive') }}</button>
                        @endif
                        
                        <button class="btn btn-primary" type="submit" name="save" value="1">{{ trans('adressatenForm.save') }}</button>
                        
                    </div>
                    
                    {!! Form::close() !!}
                </div>
                <br>
                @endforeach
            </div>
        </div>
    </div>
</fieldset>

<div class="clearfix"></div> <br>

@stop
