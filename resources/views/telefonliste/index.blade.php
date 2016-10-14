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
                               
                                <input type="text" class="form-control" name="search"
                                placeholder="{{ trans('telefonListeForm.search').' '.trans('telefonListeForm.searchTextOptions') }}" required
                                @if(isset($searchParameter)) value="{{$searchParameter}}" @endif>
                                <span class="custom-input-group-btn">
                                    <button type="submit" class="btn btn-primary no-margin-bottom" title="{{ trans('telefonListeForm.search') }}">
                                        <!--<i class="fa fa-search"></i>-->Suche
                                    </button>
                                </span> 
                               
                            </div>
                            
                            {{-- <div class="col-xs-12 col-md-4">
                                <div class="checkbox">
                                    <input type="checkbox" name="deletedUsers" id="deletedUsers" value="{{old('deletedUsers')}}">
                                    <label for="deletedUsers">{{ trans('telefonListeForm.show-deleted-users') }}</label>
                                </div>
                                 <div class="checkbox">  
                                    <input type="checkbox" name="deletedMandants" id="deletedMandants" value="{{old('deletedMandants')}}">
                                    <label for="deletedMandants">{{ trans('telefonListeForm.show-deleted-mandants') }}</label>
                                </div>
                            </div> --}}
                            
                            {{ Form::close() }}
                            <div class="col-xs-12 col-md-4 form-inline">
                                <div class="pull-right">
                                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#darstellung">
                                        <!--<i class="fa fa-eye"></i> -->
                                        {{ trans('telefonListeForm.appearance') }}
                                    </a>
                                    @if( ViewHelper::universalHasPermission(array(20)) ) 
                                        <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#export">
                                            <!--<i class="fa fa-file-excel-o "></i> -->
                                            {{ trans('telefonListeForm.export') }}
                                        </a>
                                    @endif
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
        
        @if( !empty($search) && $search == true )
            <h2 class="title">Suchergebnisse für Mandanten ({{count($mandants)}})</h2>
        @else
            <h2 class="title">Übersicht</h2>
        @endif
        
        @if( count($mandants) > 0 )
            <div class="panel-group" role="tablist" data-multiselectable="true" aria-multiselectable="true">
        
                @foreach($mandants as $mandant)
                    <div id="panel-{{$mandant->id}}" class="panel panel-primary">
                        
                        <div class="panel-heading">
                            <h4 class="panel-title transform-normal col-xs-10">
                                <a data-toggle="collapse" data-target="#collapseMandant{{$mandant->id}}" class="collapsed transform-normal" 
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
                            <div class="panel-body">
                                <table class="table data-table">
                                    <thead>
                                    <tr>
                                        <th class="@if(!isset($visible['col1'])) col-hide @endif col1 no-sort">{{ trans('telefonListeForm.photo') }} </th>
                                        <th class="@if(!isset($visible['col2'])) col-hide @endif col2">{{ trans('telefonListeForm.title') }} </th>
                                        <th class="@if(!isset($visible['col3'])) col-hide @endif col3">{{ trans('telefonListeForm.firstname') }} </th>
                                        <th class="@if(!isset($visible['col4'])) col-hide @endif col4 defaultSort">{{ trans('telefonListeForm.lastname') }} </th>
                                        <th class="@if(!isset($visible['col5'])) col-hide @endif col5 no-sort">{{ trans('telefonListeForm.role') }} </th>
                                        <th class="@if(!isset($visible['col6'])) col-hide @endif col6 no-sort">{{ trans('telefonListeForm.phone') }} </th>
                                        <th class="@if(!isset($visible['col7'])) col-hide @endif col7 no-sort">{{ trans('telefonListeForm.fax') }} </th>
                                    </tr>
                                    </thead>
                                    <tbody> 
                                    {{-- dd(array_pluck($mandant->usersInternal,'role_id')) --}}
                                        @foreach($mandant->usersInternal as $internal)
                                            <tr>
                                                <td>
                                                    @if(isset($internal->user->picture) && $internal->user->picture)
                                                        <img class="img-responsive img-phonelist" src="{{url('/files/pictures/users/'. $internal->user->picture)}}"/>
                                                    @else
                                                        <img class="img-responsive img-phonelist" src="{{url('/img/user-default.png')}}"/>
                                                    @endif
                                                </td>
                                                <td>{{ $internal->user->title }}</td>
                                                <td>{{ $internal->user->first_name }}</td>
                                                <td>{{ $internal->user->last_name }}</td>
                                                <td>{{ $internal->role->name }}</td>
                                                <td>{{ $internal->user->phone }}</td>
                                                <td>{{ $internal->user->phone_short }}</td>
                                            </tr>
                                        @endforeach
                                        
                                        @foreach($mandant->usersInMandants as $user)
                                            {{-- @if(ViewHelper::phonelistVisibility($user, $mandant)) --}}
                                            <tr>
                                                <td width="60">
                                                    @if(isset($user->picture) && $user->picture)
                                                        <img class="img-responsive img-phonelist" src="{{url('/files/pictures/users/'. $user->picture)}}"/>
                                                    @else
                                                        <img class="img-responsive img-phonelist" src="{{url('/img/user-default.png')}}"/>
                                                    @endif
                                                </td>
                                                <td>{{ $user->title }}</td>
                                                <td>{{ $user->first_name }}</td>
                                                <td>{{ $user->last_name }}</td>
                                                <td>
                                                    @foreach( $user->mandantRoles as $mandantUserRole)
                                                        @if(ViewHelper::getMandant(Auth::user()->id)->rights_admin || ViewHelper::universalHasPermission())
                                                            @if( $mandantUserRole->role->phone_role || $mandantUserRole->role->mandant_role )
                                                                @if( !in_array($mandantUserRole->role->id, array_pluck($mandant->usersInternal,'role_id')) )
                                                                    {{ ( $mandantUserRole->role->name ) }}
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if($mandant->rights_admin)
                                                                @if( $mandantUserRole->role->phone_role )
                                                                    @if( !in_array($mandantUserRole->role->id, array_pluck($mandant->usersInternal,'role_id')) )
                                                                        {{ ( $mandantUserRole->role->name ) }}
                                                                    @endif
                                                                @endif
                                                            @else
                                                                @if( $mandantUserRole->role->mandant_role )
                                                                    @if( !in_array($mandantUserRole->role->id, array_pluck($mandant->usersInternal,'role_id')) )
                                                                        {{ ( $mandantUserRole->role->name ) }}
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>{{ $user->phone }}</td>
                                                <td>{{ $user->phone_short }}</td>
                                            </tr>
                                            {{-- @endif --}}
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                @endforeach
            
            </div>
        @else
            @if(empty($search)) <h2 class="title">{{ trans('telefonListeForm.noResults') }}</h2> @endif
        @endif
        
        
        @if( !empty($search) && $search == true )
    
            <h2 class="title">Suchergebnisse für Benutzer ({{count($users)}})</h2>
        
            @if(count($users)) 
                
            
                <div class="panel panel-primary" id="panelUsers">
                        
                    <div class="panel-heading">
                        <h4 class="panel-title col-xs-10">
                            <a data-toggle="collapse" data-target="#userSearch" href="#userSearch"> 
                                Liste der Benutzer
                            </a>
                        </h4>
                    </div>
                    
                    <div id="userSearch" class="panel-collapse collapse in" role="tabpanel">
                        <div class="panel-body">
                            <table class="table data-table">
                                <thead>
                                <tr>
                                    <th class="no-sort">{{ trans('telefonListeForm.photo') }} </th>
                                    <th>{{ trans('telefonListeForm.title') }} </th>
                                    <th>{{ trans('telefonListeForm.firstname') }} </th>
                                    <th>{{ trans('telefonListeForm.lastname') }} </th>
                                    <th>{{ trans('telefonListeForm.mandant') }} </th>
                                    <th class="no-sort">{{ trans('telefonListeForm.role') }} </th>
                                    <th class="no-sort">{{ trans('telefonListeForm.phone') }} </th>
                                    <th class="no-sort">{{ trans('telefonListeForm.fax') }} </th>
                                </tr>
                                </thead>
                                <tbody> 
                                
                                @foreach($usersInternal as $internal)
                                    <tr>
                                        <td>
                                            @if(isset($internal->user->picture) && $internal->user->picture)
                                                <img class="img-responsive img-phonelist" src="{{url('/files/pictures/users/'. $internal->user->picture)}}"/>
                                            @else
                                                <img class="img-responsive img-phonelist" src="{{url('/img/user-default.png')}}"/>
                                            @endif
                                        </td>
                                        <td>{{ $internal->user->title }}</td>
                                        <td>{{ $internal->user->first_name }}</td>
                                        <td>{{ $internal->user->last_name }}</td>
                                        <td>
                                            ({{$internal->mandant->mandant_number}})
                                            {{ $internal->mandant->kurzname }}
                                        </td>
                                        <td>{{ $internal->role->name }}</td>
                                        <td>{{ $internal->user->phone }}</td>
                                        <td>{{ $internal->user->phone_short }}</td>
                                    </tr>
                                @endforeach
                                
                                @foreach( $users as $user)
                                    <tr>
                                        <td>
                                            @if(isset($user->picture) && $user->picture)
                                                <img class="img-responsive img-phonelist" src="{{url('/files/pictures/users/'. $user->picture)}}"/>
                                            @else
                                                <img class="img-responsive img-phonelist" src="{{url('/img/user-default.png')}}"/>
                                            @endif
                                        </td>
                                        <td>{{ $user->title }}</td>
                                        <td>{{ $user->first_name }}</td>
                                        <td>{{ $user->last_name }}</td>
                                        <td>
                                            ({{ ViewHelper::getMandant($user->id)->mandant_number}})
                                            {{ ViewHelper::getMandant($user->id)->kurzname }}
                                        </td>
                                        <td>
                                            
                                            @foreach( $user->mandantRoles as $mandantUserRole)
                                                @if(ViewHelper::getMandant(Auth::user()->id)->rights_admin || ViewHelper::universalHasPermission())
                                                    @if( $mandantUserRole->role->phone_role || $mandantUserRole->role->mandant_role )
                                                        @if( !in_array($mandantUserRole->role->id, array_pluck($usersInternal,'role_id')) )
                                                            {{ ( $mandantUserRole->role->name ) }}
                                                        @endif
                                                    @endif
                                                @else
                                                    @if($mandant->rights_admin)
                                                        @if( $mandantUserRole->role->phone_role )
                                                            @if( !in_array($mandantUserRole->role->id, array_pluck($usersInternal,'role_id')) )
                                                                {{ ( $mandantUserRole->role->name ) }}
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if( $mandantUserRole->role->mandant_role )
                                                            @if( !in_array($mandantUserRole->role->id, array_pluck($usersInternal,'role_id')) )
                                                                {{ ( $mandantUserRole->role->name ) }}
                                                            @endif
                                                        @endif
                                                    @endif
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
                    
                </div>
            
                
            @endif
            
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
            
            {{ Form::open(['action' => 'TelephoneListController@displayOptions', 'method' => 'POST']) }}
                
                <div class="modal-body">
                    
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-inline">
                                <label>Sichtbare Tabellenspalten</label>
                                <select class="form-control select" name="visibleColumns[]" data-placeholder="Sichtbare Tabellenspalten" multiple required>
                                    <option value="col1" @if(isset($visible['col1'])) selected @endif>Foto</option>
                                    <option value="col2" @if(isset($visible['col2'])) selected @endif>Anrede</option>
                                    <option value="col3" @if(isset($visible['col3'])) selected @endif>Vorname</option>
                                    <option value="col4" @if(isset($visible['col4'])) selected @endif>Nachname</option>
                                    <option value="col5" @if(isset($visible['col5'])) selected @endif>Abteilung</option>
                                    <option value="col6" @if(isset($visible['col6'])) selected @endif>Telefon</option>
                                    <option value="col7" @if(isset($visible['col7'])) selected @endif>Fax</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ trans('telefonListeForm.save') }}</button>
                </div>
                
            {{ Form::close() }}
            
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
                                    <option value="0" selected>Alle</option>
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
                                    <option value="1">Option 1 - Partner Gesamt</option>
                                    <option value="2">Option 2 - Einteilung Mandanten - Neptun-Mitarbeiter</option>
                                    <option value="3">Option 3 - Adressliste Mandanten-Gesamt</option>
                                    <option value="4">Option 4 - Partner Gesamt</option>
                                    <option value="5">Option 5 - Zeitarbeits-Partner</option>
                                    <option value="6">Option 6 - Bankverbindungen</option>
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
                            <dt>Name</dt> 
                            <dd>{{$mandant->name}}</dd>
                            
                            <dt>Mandantnummer</dt> 
                            <dd>{{$mandant->mandant_number}}</dd>
                            
                            <dt>Mandantname Kurz</dt> 
                            <dd>{{$mandant->kurzname}}</dd>
                            
                            <dt>Adresszusatz</dt> 
                            <dd>{{$mandant->adresszusatz}}</dd>
                            
                            <dt>Strasse/ Nr.</dt> 
                            <dd>{{$mandant->strasse}}/ {{$mandant->hausnummer}}</dd>
                            
                            <dt>PLZ/ Ort</dt> 
                            <dd>{{$mandant->plz}}/ {{$mandant->ort}}</dd>
                            
                            <dt>Bundesland</dt> 
                            <dd>{{$mandant->bundesland}}</dd>
                            
                            <dt>Telefon</dt> 
                            <dd>{{$mandant->telefon}}</dd>
                            
                            <dt>Kurzwahl</dt> 
                            <dd>{{$mandant->kurzwahl}}</dd>
                            
                            <dt>Fax</dt> 
                            <dd>{{$mandant->fax}}</dd>
                            
                            <dt>E-Mail</dt> 
                            <dd><a href="mailto:{{$mandant->email}}">{{$mandant->email}}</a></dd>
                            
                            <dt>Website</dt> 
                            <dd><a target="_blank" href="{{$mandant->website}}">{{$mandant->website}}</a></dd>
                        </dl>
                    </div>
                    
                    <div class="col-xs-2">
                        @if($mandant->logo)
                            <img class="img-responsive" src="{{url('/files/pictures/mandants/'. $mandant->logo)}}"/>
                        @else
                            <img class="img-responsive" src="{{url('/img/mandant-default.png')}}"/>
                        @endif
                    </div>
                </div>
                
                @if(!$mandant->hauptstelle)
                <div class="row hauptstelle">
                    <div class="col-xs-10">
                        <h4>Hauptstelle</h4>
                        <dl class="dl-horizontal">
                            
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
                            
                            <dt>Kurzwahl</dt> 
                            <dd>{{$mandant->kurzwahl}}</dd>
                            
                            <dt>Fax</dt> 
                            <dd>{{ViewHelper::getHauptstelle($mandant)->fax}}</dd>
                            
                            <dt>E-Mail</dt> 
                            <dd><a href="mailto:{{ViewHelper::getHauptstelle($mandant)->email}}">{{ViewHelper::getHauptstelle($mandant)->email}}</a></dd>
                            
                            <dt>Website</dt> 
                            <dd><a target="_blank" href="{{$mandant->website}}">{{ViewHelper::getHauptstelle($mandant)->website}}</a></dd>
                        </dl>
                    </div>
                    
                    <div class="col-xs-2">
                        @if(ViewHelper::getHauptstelle($mandant)->logo)
                            <img class="img-responsive" src="{{url('/files/pictures/mandants/'. ViewHelper::getHauptstelle($mandant)->logo)}}"/>
                        @else
                            <img class="img-responsive" src="{{url('/img/mandant-default.png')}}"/>
                        @endif
                    </div>
                </div>
                @endif
                
                @if( ViewHelper::universalHasPermission( array(19,20) ) == true  )
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
                                <dd>{{ Carbon\Carbon::parse( $mandant->mandantInfo->erlaubniss_gultig_ab)->format('d.m.Y h:i:s') }}</dd>
                                
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
                                
                                @if( ViewHelper::universalHasPermission( array(20) ) == true  )
                                    <dt>Bankverbindungen</dt> 
                                    <dd>{!! str_replace(array('[',']'), array('','<br>'), $mandant->mandantInfo->bankverbindungen) !!}</dd>
                                @endif
                                <dt>Sonstiges</dt> 
                                
                                <dd>{{ $mandant->mandantInfo->info_sonstiges }}</dd>
                                
                            </dl>
                        </div>
                    </div>
                    @endif
                @endif
                
            </div>
            
            <div class="modal-footer">
               
                @if( ViewHelper::universalHasPermission( array(20) ) == true  )
                <!--this was wrapped around the button-> then changed with task NEPTUN-303 -->
                @endif
                <a target="_blank" href="{{url('/telefonliste/'.$mandant->id.'/pdf')}}" class="btn btn-primary">
                    <!--<i class="fa fa-file-pdf-o"></i>-->
                    {{ trans('telefonListeForm.pdf-export') }}
                </a>
                
            </div>
        </div>
    </div>
</div>

@endforeach


@stop
