{{-- ISO KATEGORIEN --}}

@extends('master')

@section('content')

<div class="row">
    <div class="col-xs-12 col-md-12 white-bgrnd">
        <div class="fixed-row">
            <div class="fixed-position ">
                <h1 class="page-title">
                    {{ trans('isoKategorienForm.iso') }} {{ trans('isoKategorienForm.category') }} {{ trans('isoKategorienForm.management') }}
                </h1>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

<fieldset class="form-group">
    
    <h4 class="title">{{ trans('isoKategorienForm.category') }} {{ trans('isoKategorienForm.add') }}</h4>
    
    <div class="row">
        <!-- input box-->
        <div class="col-lg-4"> 
            {!! Form::open(['route' => 'iso-kategorien.store']) !!}
                <div class="form-group">
                    {!! ViewHelper::setInput('name', '', old('name'), trans('isoKategorienForm.name'), trans('isoKategorienForm.name'), true) !!} 
                    
                    <div class="checkbox">
                        <br>
                        <input class="hide-input" id="hide-input" data-hide-target="iso-categories" data-disable-target="iso-categories" type="checkbox" name="parent"/>
                        <label for="hide-input">{{ trans('isoKategorienForm.parent-category') }}</label>
                    </div>
                </div>
                <div class="form-group" data-hide="iso-categories">
                    <label>{{ trans('isoKategorienForm.parent-category') }}</label>
                   
                    <select name="category_id" class="form-control select" data-disable="iso-categories" data-placeholder="{{ trans('isoKategorienForm.parent-category-select') }}">
                         <option value=""></option>
                         @foreach($isoCategories as $isoCategory)
                             @if($isoCategory->parent)
                                 <option value="{{ $isoCategory->id }}"> {{ $isoCategory->name }} </option>
                             @endif
                         @endforeach
                    </select>
                </div>
                <button class="btn btn-primary">{{ trans('isoKategorienForm.add') }} </button>
            {!! Form::close() !!}
        </div><!--End input box-->
    </div>
    
</fieldset>


<fieldset class="form-group">
    
    <h4 class="title">{{ trans('isoKategorienForm.category') }} {{ trans('isoKategorienForm.overview') }}</h4>
     
    <div class="row">
        <div class="col-md-9">
            <table class="table">
                <tr>
                    <th colspan="3">
                        {{ trans('isoKategorienForm.categories') }}
                    </th>
                </tr>
                @foreach($isoCategories as $isoCategory)
               <tr>
                   {!! Form::open(['route' => ['iso-kategorien.update', 'iso-kategorien' => $isoCategory->id], 'method' => 'PATCH']) !!}
                    <td class="col-xs-5 vertical-center">
                         <input type="text" class="form-control" name="name" placeholder="Name" value="{{ $isoCategory->name }}" required/>
                    </td>
                    <td class="col-xs-4 vertical-center">
                        @if($isoCategory->parent)
                           <p>{{ trans('isoKategorienForm.parent-category') }}</p>
                        @else
                         <select name="category_id" class="form-control select" data-placeholder="{{ trans('isoKategorienForm.parent-category-select') }}">
                             <option value=""></option>
                             @foreach($isoCategories as $isoCategoryChild)
                                 @if($isoCategoryChild->parent)
                                     <option value="{{ $isoCategoryChild->id }}" @if($isoCategory->iso_category_parent_id == $isoCategoryChild->id) selected @endif > {{ $isoCategoryChild->name }} </option>
                                 @endif
                             @endforeach
                         </select>
                        @endif
                    </td>
                    <td class="col-xs-3 text-right table-options">

                        @if($isoCategory->active)
                        <button class="btn btn-success" type="submit" name="activate" value="1">{{ trans('adressatenForm.active') }}</button>
                        @else
                        <button class="btn btn-danger" type="submit" name="activate" value="0">{{ trans('adressatenForm.inactive') }}</button>
                        @endif
                        
                        <button class="btn btn-primary" type="submit">{{ trans('adressatenForm.save') }}</button>
                        
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
