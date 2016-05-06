{{-- ISO KATEGORIEN --}}

@extends('master')

@section('content')

<h1 class="text-primary">{{ trans('isoKategorienForm.iso') }} {{ trans('isoKategorienForm.category') }} {{ trans('isoKategorienForm.management') }}</h1>

<fieldset class="form-group">
    
    <h4>{{ trans('isoKategorienForm.category') }} {{ trans('isoKategorienForm.add') }}</h4>
    
    <div class="row">
        <!-- input box-->
        <div class="col-lg-4"> 
            {!! Form::open(['route' => 'iso-kategorien.store']) !!}
                <div class="form-group">
                    {!! ViewHelper::setSelect(null, 'iso_category_id', '', old('iso_category_id'), trans('isoKategorienForm.parent-category'), trans('isoKategorienForm.parent-category'), false) !!}
                </div>
                <div class="form-group">
                    {!! ViewHelper::setInput('name', '', old('name'), trans('isoKategorienForm.name'), trans('isoKategorienForm.name'), true) !!} 
                </div>
                <button class="btn btn-primary">{{ trans('isoKategorienForm.add') }} </button>
            {!! Form::close() !!}
        </div><!--End input box-->
    </div>
    
</fieldset>


<fieldset class="form-group">
    
    <h4>{{ trans('isoKategorienForm.category') }} {{ trans('isoKategorienForm.overview') }}</h4>
     
    <div class="row">
        <div class="col-md-8">
            <table class="table">
                <tr>
                    <th colspan="3">
                        {{ trans('isoKategorienForm.categories') }}
                    </th>
                </tr>
                @for($i=1; $i < 5; $i++)
               <tr>
                    <td class="col-xs-5">
                         <select name="iso_category_id" class="form-control select" data-placeholder="Kategorie">
                             <option value=""></option>
                             <option value="1">Kategorie 1</option>
                             <option value="2">Kategorie 2</option>
                         </select>
                    </td>
                    <td class="col-xs-4">
                         <input type="text" class="form-control" name="name" placeholder="Name"/>
                    </td>
                    <td class="col-xs-3 text-center table-options">
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
