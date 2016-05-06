{{-- ADRESSATEN --}}

@extends('master')

@section('content') 

<h1 class="text-primary">{{ trans('adressatenForm.adressats') }} {{ trans('adressatenForm.management') }}</h1>

<fieldset class="form-group">
    
    <h4>{{ trans('adressatenForm.adressat') }} {{ trans('adressatenForm.add') }}</h4>
    
    <div class="row">
        <!-- input box-->
        <div class="col-lg-6"> 
            {!! Form::open(['route' => 'adressaten.store']) !!}
            <div class="form-inline">
                {!! ViewHelper::setInput('name','', old('name'), trans('adressatenForm.name'), trans('adressatenForm.name'), true) !!} 
                <button class="btn btn-primary">{{ trans('adressatenForm.add') }} </button>
            </div>
            {!! Form::close() !!}
        </div><!--End input box-->
    </div>
    
</fieldset>


<fieldset class="form-group">
    
    <h4>{{ trans('adressatenForm.adressats') }} {{ trans('adressatenForm.overview') }}</h4>
     
    <div class="row">
        <div class="col-lg-6">
            <table class="table">
                <tr>
                    <th colspan="2">
                        {{ trans('adressatenForm.adressat') }}
                    </th>
                </tr>
                @for($i=1; $i < 5; $i++)
                <tr>
                    <td>
                         <input type="text" class="form-control" name="name" placeholder="Name"/>
                    </td>
                    <td>
                        <button class="btn btn-white">{{ trans('adressatenForm.active') }}</button>
                        <button class="btn btn-primary">{{ trans('adressatenForm.save') }}</button>
                    </td>
                </tr>
                @endfor
            </table>
        </div>
    </div>
    
</fieldset>

<div class="clearfix"></div> <br>

@stop
