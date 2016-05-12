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
                <label> {{ trans('adressatenForm.name') }} <i class="fa fa-asterisk text-info"></i></label>
                <input type="text" class="form-control" name="name" placeholder="{{ trans('adressatenForm.name') }}" required/>
                <button class="btn btn-primary"> {{ trans('adressatenForm.add') }} </button>
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
                @foreach($adressate as $adressat)
                <tr>
                    {!! Form::open(['route' => ['adressaten.update', 'adressaten'=> $adressat->id], 'method' => 'PATCH']) !!}
                    <td class="col-sm-8">
                         <input type="text" class="form-control" name="name" value="{{ $adressat->name }}" placeholder="Name"/>
                    </td>
                    <td class="col-sm-4 table-options">
                        
                        @if($adressat->active)
                            <button class="btn btn-success" type="submit" name="activate" value="1">{{ trans('adressatenForm.active') }}</button>
                        @else
                            <button class="btn btn-danger" type="submit" name="activate" value="0">{{ trans('adressatenForm.inactive') }}</button>
                        @endif
                        
                        <button class="btn btn-primary" type="submit" name="save" value="1">{{ trans('adressatenForm.save') }}</button>
                        
                    </td>
                    {!! Form::close() !!}
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    
</fieldset>

<div class="clearfix"></div> <br>

@stop
