{{-- TELEFONLISTE --}}

@extends('master')

@section('page-title') {{ trans('telefonListeForm.phone-list') }} @stop

@section('content')

<fieldset class="telefonliste forms">
    
    <div class="row">
        {{ Form::open(['action' => 'SearchController@searchPhoneList', 'method' => 'POST']) }}
            <div class="col-xs-12">
                <div class="box-wrapper">
                    <h4 class="title">{{ trans('telefonListeForm.search-options') }}</h4>
                     <div class="box">
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-xs-12 col-md-5 form-group no-margin-bottom">
                               
                                <input type="text" class="form-control" name="parameter" placeholder="{{ trans('telefonListeForm.search').' '.trans('telefonListeForm.mandants').'/'. trans('telefonListeForm.user') }}" required>
                                <span class="custom-input-group-btn">
                                    <button type="submit" name="search" class="btn btn-primary no-margin-bottom" title="{{ trans('telefonListeForm.search') }}">
                                        <!--<i class="fa fa-search"></i>-->Suche
                                    </button>
                                </span> 
                               
                            </div>
                            <div class="col-xs-12 col-md-4">
                                <div class="checkbox">
                                    <input type="checkbox" name="deletedUsers" id="deletedUsers">
                                    <label for="deletedUsers">{{ trans('telefonListeForm.show-deleted-users') }}</label>
                                </div>
                                 <div class="checkbox">  
                                    <input type="checkbox" name="deletedMandants" id="deletedMandants">
                                    <label for="deletedMandants">{{ trans('telefonListeForm.show-deleted-mandants') }}</label>
                                </div>
                            </div>
                            {{ Form::close() }}
                            <div class="col-xs-12 col-md-3 form-inline">
                                <div>
                                    <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#darstellung">
                                        <i class="fa fa-eye"></i> {{ trans('telefonListeForm.appearance') }}
                                    </a>
                                    <a href="#" class="btn btn-primary pull-right" data-toggle="modal" data-target="#export">
                                        <i class="fa fa-file-excel-o "></i> {{ trans('telefonListeForm.export') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

</fieldset>


<div class="row">
    <div class="col-xs-12">
        <h4 class="title">{{ trans('telefonListeForm.overview') }}</h4>
        
        <div class="panel-group" role="tablist" data-multiselectable="true" aria-multiselectable="true">
        
        @for($i = 1; $i < 5; $i++)
            <div id="panel-{{$i}}" class="panel panel-primary">
                
                <div class="panel-heading" role="tab" id="heading-{{$i}}">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-{{$i}}" aria-expanded="false" aria-controls="collapse-{{$i}}">
                            {{ trans('telefonListeForm.mandant') }} #{{$i}} (8 Benutzer)
                        </a>
                        <span class="pull-right">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#details">{{ trans('telefonListeForm.details') }}</button>
                        </span>
                    </h4>
                </div>
                
                <div id="collapse-{{$i}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{$i}}">
                    <table class="table data-table">
                        <thead>
                        <tr>
                            <th class="no-sort">{{ trans('telefonListeForm.photo') }} </th>
                            <th>{{ trans('telefonListeForm.title') }} </th>
                            <th>{{ trans('telefonListeForm.firstname') }} </th>
                            <th>{{ trans('telefonListeForm.lastname') }} </th>
                            <th>{{ trans('telefonListeForm.phone') }} </th>
                            <th>{{ trans('telefonListeForm.fax') }} </th>
                        </tr>
                        </thead>
                        <tbody>
                        @for($j = 1; $j < 5; $j++)
                        <tr>
                            <td><img src="http://placehold.it/60x60"></td>
                            <td>Herr</td>
                            <td>Max</td>
                            <td>Mustermann</td>
                            <td>+123 45 678 9000</td>
                            <td>+123 45 678 9999</td>
                        </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
                
            </div>
        @endfor
        
        </div>
        
    </div>
</div>


<div class="clearfix"></div> <br>

<div class="modal fade" id="darstellung" tabindex="-1" role="dialog" aria-labelledby="darstellung" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-close"></span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('telefonListeForm.appearance') }}</h4>
            </div>
            
            <div class="modal-body">
                
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-inline">
                            <label>Sichtbare Tabellenspalten</label>
                            <select class="form-control select" multiple>
                                <option value="1" selected>Spalte 1</option>
                                <option value="2" selected>Spalte 2</option>
                                <option value="3">Spalte 3</option>
                                <option value="4">Spalte 4</option>
                                <option value="5" selected>Spalte 5</option>
                                <option value="6">Spalte 6</option>
                            </select>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">{{ trans('telefonListeForm.save') }}</button>
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="export" tabindex="-1" role="dialog" aria-labelledby="export" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-close"></span></button>
                <h4 class="modal-title" id="myModalLabel">Export</h4>
            </div>
            
            <div class="modal-body">
                
                <div class="row">
                    <div class="col-xs-12">
                        <div class="form-inline">
                            <label>{{ trans('telefonListeForm.mandants') }}</label>
                            <select class="form-control select" multiple>
                                <option value="1" selected>001</option>
                                <option value="2" selected>002</option>
                                <option value="3">003</option>
                                <option value="4">004</option>
                                <option value="5" selected>005</option>
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div><br>
                    <div class="col-xs-12">
                        <div class="form-inline">
                            <label>{{ trans('telefonListeForm.options') }}</label>
                            <select class="form-control select" >
                                <option value="1" selected>Option 1</option>
                                <option value="1" selected>Option 2</option>
                                <option value="1" selected>Option 3</option>
                            </select>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">{{ trans('telefonListeForm.export') }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="details" tabindex="-1" role="dialog" aria-labelledby="details" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-close"></span></button>
                <h4 class="modal-title" id="myModalLabel">Details: Neptun GmbH</h4>
            </div>
            
            <div class="modal-body">
                
                <div class="row general">
                    <div class="col-xs-12">
                        <h4>Allgemeine Infos</h4>
                        <p>Bacon ipsum dolor amet prosciutto doner pork loin ham chicken. Tail andouille beef, 
                        turducken pork loin biltong corned beef tenderloin t-bone brisket short loin. Beef ribs alcatra jerky ham ribeye tri-tip, 
                        doner tail ground round shoulder filet mignon cow meatloaf bresaola flank. Biltong chicken boudin jerky t-bone. 
                        Short loin beef ribs capicola hamburger andouille, shoulder biltong ham beef bacon fatback venison rump tongue.</p> 
                    </div>
                </div>
                
                <div class="row general">
                    <div class="col-xs-12">
                        <h4>Allgemeine Infos</h4>
                        <p>Bacon ipsum dolor amet prosciutto doner pork loin ham chicken. Tail andouille beef, 
                        turducken pork loin biltong corned beef tenderloin t-bone brisket short loin. Beef ribs alcatra jerky ham ribeye tri-tip, 
                        doner tail ground round shoulder filet mignon cow meatloaf bresaola flank. Biltong chicken boudin jerky t-bone. 
                        Short loin beef ribs capicola hamburger andouille, shoulder biltong ham beef bacon fatback venison rump tongue.</p> 
                    </div>
                </div>
                
                <div class="row general">
                    <div class="col-xs-12">
                        <h4>Allgemeine Infos</h4>
                        <p>Bacon ipsum dolor amet prosciutto doner pork loin ham chicken. Tail andouille beef, 
                        turducken pork loin biltong corned beef tenderloin t-bone brisket short loin. Beef ribs alcatra jerky ham ribeye tri-tip, 
                        doner tail ground round shoulder filet mignon cow meatloaf bresaola flank. Biltong chicken boudin jerky t-bone. 
                        Short loin beef ribs capicola hamburger andouille, shoulder biltong ham beef bacon fatback venison rump tongue.</p> 
                    </div>
                </div>
                
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">
                    <i class="fa fa-file-pdf-o"></i>
                    {{ trans('telefonListeForm.pdf-export') }}
                </button>
            </div>
        </div>
    </div>
</div>

@stop
