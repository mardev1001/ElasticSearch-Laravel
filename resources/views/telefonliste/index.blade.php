{{-- TELEFONLISTE --}}

@extends('master')

@section('page-title') {{ trans('telefonListeForm.phone-list') }} @stop

    @section('bodyClass')
        phonelist
    @stop

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
                            <div class="col-xs-12 col-md-4 form-group no-margin-bottom">
                               
                                <input type="text" class="form-control" name="parameter" placeholder="{{ trans('telefonListeForm.search').' '.trans('telefonListeForm.mandants').'/ '.trans('telefonListeForm.mandantNumber').'/ '. trans('telefonListeForm.user') }}" required>
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
                            <div class="col-xs-12 col-md-4 form-inline">
                                <div class="pull-right">
                                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#darstellung">
                                        <i class="fa fa-eye"></i> {{ trans('telefonListeForm.appearance') }}
                                    </a>
                                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#export">
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
        @if( count($mandants) > 0 )
            <h4 class="title">{{ trans('telefonListeForm.overview') }}</h4>
            <div class="panel-group" role="tablist" data-multiselectable="true" aria-multiselectable="true">
        
        @foreach($mandants as $mandant)
            <div id="panel-{{$mandant->id}}" class="panel panel-primary">
                
                <div class="panel-heading">
                        <h4 class="panel-title col-xs-10">
                                <a data-toggle="collapse" data-target="#collapseMandant{{$mandant->id}}" class="collapsed" 
                                   href="#collapseMandant{{$mandant->id}}" 
                                   @if(isset($mandant->openTreeView) ) data-open="true" @endif 
                                   {{-- if searched for user open the mandant --}}
                                   > 
                                   
                                  ({{$mandant->mandant_number}}) {{$mandant->kurzname}} 
                                  @if($mandant->hauptstelle) [Hauptstelle] 
                                  @else [Filiale - {{ViewHelper::getHauptstelle($mandant)->mandant_number}}]
                                  @endif
                                </a>
                            
                        </h4>
                        
                        <span class="panel-options col-xs-2 no-margin-top">
                            <span class="pull-right">
                                <a href="#" data-toggle="modal" data-target="#details{{$mandant->id}}" class="btn btn-primary no-arrow"> Detailansicht </a> 
                            </span>
                        </span>
                        
                    </div>
                
                <div id="collapseMandant{{$mandant->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{$mandant->id}}">
                    <table class="table data-table">
                        <thead>
                        <tr>
                            <th class="no-sort">{{ trans('telefonListeForm.photo') }} </th>
                            <th>{{ trans('telefonListeForm.title') }} </th>
                            <th>{{ trans('telefonListeForm.firstname') }} </th>
                            <th>{{ trans('telefonListeForm.lastname') }} </th>
                            <th class="no-sort">{{ trans('telefonListeForm.role') }} </th>
                            <th class="no-sort">{{ trans('telefonListeForm.phone') }} </th>
                            <th class="no-sort">{{ trans('telefonListeForm.fax') }} </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($mandant->usersInMandants as $user)
                        <tr>
                            <td><img src="http://placehold.it/60x60"></td>
                            <td>{{ $user->title }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            <td>
                                @foreach( $user->mandantRoles as $mandantUserRole)
                                    @if( $mandantUserRole->role->phone_role == 1 || $mandantUserRole->role->id == 21 || $mandantUserRole->role->id == 23
                                    || $mandantUserRole->role->name == 'Geschäftsführer' || $mandantUserRole->role->name == 'Qualitätsmanager' 
                                    || $mandantUserRole->role->name == 'Rechntabteilung' )
                                        {{ ( $mandantUserRole->role->name ) }}
                                    @endif      
                                @endforeach
                            </td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->phone_short }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>
        @endforeach
        
        </div>
        @else
            <h4 class="title">{{ trans('telefonListeForm.noResults') }}</h4>
        @endif
        
        
        
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
            
            {{ Form::open(['action' => 'TelephoneListController@xlsExport', 'method' => 'POST']) }}
                <div class="modal-body">
                    
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-inline">
                                <label>{{ trans('telefonListeForm.mandants') }}</label>
                                <select name="export-mandants[]" data-placeholder="{{ trans('telefonListeForm.mandants') }}" class="form-control select" multiple required>
                                    <option></option>
                                    @foreach($mandants as $mandant)
                                    <option value="{{$mandant->id}}">({{$mandant->mandant_number}}) {{$mandant->kurzname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div><br>
                        <div class="col-xs-12">
                            <div class="form-inline">
                                <label>{{ trans('telefonListeForm.options') }}</label>
                                <select name="export-option" data-placeholder="{{ trans('telefonListeForm.options') }}" class="form-control select" required>
                                    <option></option>
                                    <option value="1">Option 1</option>
                                    <option value="1">Option 2</option>
                                    <option value="1">Option 3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ trans('telefonListeForm.export') }}</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

@foreach($mandants as $mandant)

<div class="modal fade" id="details{{$mandant->id}}" tabindex="-1" role="dialog" aria-labelledby="details{{$mandant->id}}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-close"></span></button>
                <h4 class="modal-title" id="myModalLabel">Detailansicht: {{$mandant->name}}</h4>
            </div>
            
            <div class="modal-body">
                
                <div class="row general">
                    <div class="col-xs-10">
                        <h4>Allgemeine Informationen</h4>
                        <dl class="dl-horizontal">
                            <dt>Mandantname Kurz</dt> 
                            <dd>{{$mandant->kurzname}}</dd>
                            
                            <dt>Kurzwahl</dt> 
                            <dd>{{$mandant->kurzwahl}}</dd>
                            
                            <dt>Mandantnummer</dt> 
                            <dd>{{$mandant->mandant_number}}</dd>
                            
                            <dt>Adresszusatz</dt> 
                            <dd>{{$mandant->adresszusatz}}</dd>
                            
                            <dt>Strasse/ Nr.</dt> 
                            <dd>{{$mandant->strasse}}/ {{$mandant->hausnummer}}</dd>
                            
                            <dt>PLZ/ Ort</dt> 
                            <dd>{{$mandant->plz}}/ {{$mandant->ort}}</dd>
                            
                            <dt>Telefon</dt> 
                            <dd>{{$mandant->telefon}}</dd>
                            
                            <dt>Fax</dt> 
                            <dd>{{$mandant->fax}}</dd>
                            
                            <dt>E-Mail</dt> 
                            <dd>{{$mandant->email}}</dd>
                            
                            <dt>Website</dt> 
                            <dd><a target="_blank" href="{{$mandant->website}}">{{$mandant->website}}</a></dd>
                        </dl>
                    </div>
                    
                    <div class="col-xs-2">
                        @if($mandant->logo)
                            <img class="img-responsive" id="image-preview" src="{{url('/files/pictures/mandants/'. $mandant->logo)}}"/>
                        @else
                            <img class="img-responsive" id="image-preview" src="{{url('/img/mandant-default.png')}}"/>
                        @endif
                    </div>
                </div>
                
                @if(!$mandant->hauptstelle)
                <div class="row hauptstelle">
                    <div class="col-xs-10">
                        <h4>Hauptstelle</h4>
                        <dl class="dl-horizontal">
                            <dt>Mandantname Kurz</dt> 
                            <dd>{{ViewHelper::getHauptstelle($mandant)->kurzname}}</dd>
                            
                            <dt>Kurzwahl</dt> 
                            <dd>{{ViewHelper::getHauptstelle($mandant)->kurzwahl}}</dd>
                            
                            <dt>Mandantnummer</dt> 
                            <dd>{{ViewHelper::getHauptstelle($mandant)->mandant_number}}</dd>
                            
                            <dt>Adresszusatz</dt> 
                            <dd>{{ViewHelper::getHauptstelle($mandant)->adresszusatz}}</dd>
                            
                            <dt>Strasse/ Nr.</dt> 
                            <dd>{{ViewHelper::getHauptstelle($mandant)->strasse}}/ {{ViewHelper::getHauptstelle($mandant)->hausnummer}}</dd>
                            
                            <dt>PLZ/ Ort</dt> 
                            <dd>{{ViewHelper::getHauptstelle($mandant)->plz}}/ {{ViewHelper::getHauptstelle($mandant)->ort}}</dd>
                            
                            <dt>Telefon</dt> 
                            <dd>{{ViewHelper::getHauptstelle($mandant)->telefon}}</dd>
                            
                            <dt>Fax</dt> 
                            <dd>{{ViewHelper::getHauptstelle($mandant)->fax}}</dd>
                            
                            <dt>E-Mail</dt> 
                            <dd>{{ViewHelper::getHauptstelle($mandant)->email}}</dd>
                            
                            <dt>Website</dt> 
                            <dd><a target="_blank" href="{{$mandant->website}}">{{ViewHelper::getHauptstelle($mandant)->website}}</a></dd>
                        </dl>
                    </div>
                    
                    <div class="col-xs-2">
                        @if(ViewHelper::getHauptstelle($mandant)->logo)
                            <img class="img-responsive" id="image-preview" src="{{url('/files/pictures/mandants/'. ViewHelper::getHauptstelle($mandant)->logo)}}"/>
                        @else
                            <img class="img-responsive" id="image-preview" src="{{url('/img/mandant-default.png')}}"/>
                        @endif
                    </div>
                </div>
                @endif
                
                
                <div class="row important">
                    <div class="col-xs-12">
                        <h4>Wichtige Informationen</h4>
                        <dl class="dl-horizontal">

                            @if(isset($mandant->mandantInfo))
                            <dt>Wichtiges</dt>
                            <dd>{{$mandant->mandantInfo->info_wichtiges}}</dd>
                            @endif
                            
                            <dt>Geschäftsführer</dt> 
                            <dd>{{$mandant->geschaftsfuhrer}}</dd>
                            
                            <dt>Geschäftsführer-Informationen</dt> 
                            <dd>{{$mandant->geschaftsfuhrer_infos}}</dd>
                            
                            <dt>Geschäftsführer Von</dt> 
                            <dd>{{$mandant->geschaftsfuhrer_von}}</dd>
                            
                            <dt>Geschäftsführer Bis</dt> 
                            <dd>{{$mandant->geschaftsfuhrer_bis}}</dd>
                            
                            <dt>Geschäftsführerhistorie</dt> 
                            <dd>{{$mandant->geschaftsfuhrer_history}}</dd>
                            
                        </dl>
                    </div>
                </div>
                
                @if(isset($mandant->mandantInfo))
                <div class="row additional">
                    <div class="col-xs-12">
                        <h4>Weitere Informationen</h4>
                        <dl class="dl-horizontal">
                            
                            <dt class="col-xs-4">Prokura</dt> 
                            <dd>{{$mandant->mandantInfo->prokura}}</dd>
                            
                            <dt>Betriebsnummer</dt> 
                            <dd>{{$mandant->mandantInfo->betriebsnummer}}</dd>
                            
                            <dt>Handelsregisternummer</dt> 
                            <dd>{{$mandant->mandantInfo->handelsregister}}</dd>
                            
                            <dt>Handelsregistersitz</dt> 
                            <dd>{{$mandant->mandantInfo->Handelsregister_sitz}}</dd>
                            
                            <dt>Gewerbeanmeldung</dt> 
                            <dd>{{$mandant->mandantInfo->angemeldet_am}}</dd>
                            
                            <dt>Umgemeldet am </dt> 
                            <dd>{{$mandant->mandantInfo->umgemeldet_am}}</dd>
                            
                            <dt>Abgemeldet am</dt> 
                            <dd>{{$mandant->mandantInfo->abgemeldet_am}}</dd>
                            
                            <dt>Gewerbeanmeldung Historie</dt> 
                            <dd>{{$mandant->mandantInfo->gewerbeanmeldung_history}}</dd>
                             
                            <dt>Steuernummer</dt> 
                            <dd>{{$mandant->mandantInfo->steuernummer}}</dd>
                            
                            <dt>USt-IdNr.</dt> 
                            <dd>{{$mandant->mandantInfo->ust_ident_number}}</dd>
                            
                            <dt>Zusätzliche Informationen Steuer</dt> 
                            <dd>{{$mandant->mandantInfo->zausatzinfo_steuer}}</dd>
                            
                            <dt>Berufsgenossenschaft/ Mitgliedsnummer</dt> 
                            <dd>{{$mandant->mandantInfo->berufsgenossenschaft_number}}</dd>
                            
                            <dt>Zusätzliche Informationen Berufsgenossenschaft</dt> 
                            <dd>{{$mandant->mandantInfo->berufsgenossenschaft_zusatzinfo}}</dd>
                            
                            <dt>Erlaubnis zur Arbeitnehmerüberlassung</dt> 
                            <dd>{{$mandant->mandantInfo->berufsgenossenschaft_zusatzinfo}}</dd>
                            
                            <dt>Unbefristet</dt> 
                            @if($mandant->mandantInfo->unbefristet)
                            <dd>Ja</dd>
                            @else
                            <dd>Nein</dd>
                            @endif
                            
                            <dt>Befristet bis</dt> 
                            <dd>{{$mandant->mandantInfo->befristet_bis}}</dd>
                            
                            <dt>Zuständige Erlaubnisbehörde</dt> 
                            <dd>{{$mandant->mandantInfo->erlaubniss_gultig_von}}</dd>
                            
                            <dt>Informationen zum Geschäftsjahr</dt> 
                            <dd>{{$mandant->mandantInfo->geschaftsjahr_info}}</dd>
                            
                            <dt>Bankverbindungen</dt> 
                            <dd>{{$mandant->mandantInfo->bankverbindungen}}</dd>
                            
                            <dt>Sonstiges</dt> 
                            <dd>{{$mandant->mandantInfo->info_sonstiges}}</dd>
                            
                        </dl>
                    </div>
                </div>
                @endif
                
            </div>
            
            <div class="modal-footer">
                <a target="_blank" href="{{url('/telefonliste/'.$mandant->id.'/pdf')}}" class="btn btn-primary">
                    <i class="fa fa-file-pdf-o"></i>
                    {{ trans('telefonListeForm.pdf-export') }}
                </a>
            </div>
        </div>
    </div>
</div>

@endforeach


@stop
