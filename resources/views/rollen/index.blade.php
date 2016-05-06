{{-- ROLLENVERWALTUNG --}}

@extends('master')

@section('content')

<h1 class="text-primary">{{ trans('rollenForm.role-management') }}</h1>

<fieldset class="form-group">
    
    <h4>{{ trans('rollenForm.roles') }} {{ trans('rollenForm.add') }}</h4>
    
    <div class="row">
        <!-- input box-->
            {!! Form::open(['route' => 'rollen.store']) !!}
                <div class="col-xs-12"><strong>Rolle Anlegen</strong></div>
                <div class="col-lg-3">
                    <div class="form-group">
                        {!! ViewHelper::setInput('name', '', old('name'), trans('rollenForm.name'), trans('rollenForm.name'), true) !!} 
                    </div>
                </div>
                <div class="col-lg-3"> 
                    <div class="form-group">
                        <label>{{ trans('rollenForm.rights') }}</label>
                        <select name="" class="form-control select" data-placeholder="{{ trans('rollenForm.rights') }}" multiple>
                            <option value="0"></option>
                            <option value="1">Pflichtfeld</option>
                            <option value="2">Redaktion</option>
                            <option value="3">Mandant</option>
                            <option value="4">Wiki</option>
                            <option value="5">Telefonliste</option>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                
                <div class="col-xs-12"><strong>{{ trans('rollenForm.rights') }} {{ trans('rollenForm.copy') }}</strong><br></div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>{{ trans('rollenForm.documents') }}</label>
                        <select name="" class="form-control select">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-3"> 
                    <div class="form-group">
                        <label>{{ trans('rollenForm.wiki') }}</label>
                        <select name="" class="form-control select">
                            <option value=""></option>
                        </select>
                    </div>
                </div>
                <div class="clearfix"></div>
                
                <div class="col-lg-3">     
                    <button class="btn btn-primary">{{ trans('rollenForm.add') }} </button>
                </div>
            {!! Form::close() !!}
    </div><!--End input box-->
    
    
</fieldset>


<fieldset class="form-group">
    
    <h4>{{ trans('rollenForm.roles') }} {{ trans('rollenForm.overview') }}</h4>
     
    <div class="row">
        <div class="col-md-8">
            <table class="table">
                <tr>
                    <th colspan="3">
                        {{ trans('rollenForm.system') }} {{ trans('rollenForm.roles') }}
                    </th>
                </tr>
                    @for($i=1;$i<=6;$i++)
                    <tr>
                        {!! Form::open(['route' => ['rollen.update', $i]]) !!}
                            <td class="col-xs-5">
                                <div class="form-group">
                                    {!! ViewHelper::setInput('name', '', old('name'), trans('rollenForm.name'), trans('rollenForm.name'), true) !!} 
                                </div>
                            </td>
                            <td class="col-xs-4 vertical-center">
                                <label>{{ trans('rollenForm.editing') }}</label>
                                
                                <div class="checkbox-inline pull-right">
                                    <label><input type="checkbox" name="wiki">{{ trans('rollenForm.wiki') }}</label>
                                </div>
                            </td>
                            <td class="col-xs-3 text-center table-options vertical-center">
                                <button class="btn btn-primary">{{ trans('rollenForm.save') }}</button>
                            </td>
                        {!! Form::close() !!}
                    </tr>
                    @endfor
            </table>
        </div>
    </div>
     
    <div class="row">
        <div class="col-xs-12">
            <table class="table">
                <tr>
                    <th colspan="4">
                        {{ trans('rollenForm.user-defined') }} {{ trans('rollenForm.roles') }}
                    </th>
                </tr>
                    @for($i=1;$i<=3;$i++)
                    <tr>
                        {!! Form::open(['route' => ['rollen.update', $i]]) !!}
                            <td class="col-xs-4">
                                <div class="form-group">
                                    {!! ViewHelper::setInput('name', '', old('name'), trans('rollenForm.name'), trans('rollenForm.name'), true) !!} 
                                </div>
                            </td>
                            <td class="col-xs-4">
                                <label>{{ trans('rollenForm.rights') }}</label>
                                <select name="" class="form-control select" data-placeholder="{{ trans('rollenForm.rights') }}" multiple>
                                    <option value="0"></option>
                                    <option value="1">Pflichtfeld</option>
                                    <option value="2">Redaktion</option>
                                    <option value="3">Mandant</option>
                                    <option value="4">Wiki</option>
                                    <option value="5">Telefonliste</option>
                                </select>
                            </td>
                            <td class="col-xs-3 text-center table-options vertical-center">
                                <button class="btn btn-white">{{ trans('rollenForm.active') }}</button>
                                <button class="btn btn-primary">{{ trans('rollenForm.save') }}</button>
                            </td>
                            <td class="col-xs-1 text-center table-options vertical-center">
                                <a href="#">{{ trans('rollenForm.active-users') }}: 6</a>
                            </td>
                        {!! Form::close() !!}
                    </tr>
                    @endfor
            </table>
        </div>
    </div>
    
</fieldset>

<div class="clearfix"></div> <br>

@stop
