@extends('master')

@section('page-title')
    {{  ucfirst( trans('controller.administration') ) }} 
@stop
    @section('bodyClass')
    mandant-administration 
    @stop
    @section('content')
    <div class="col-xs-12 box-wrapper">
        <h2 class="title">{{ trans('mandantenForm.search') }} </h2>
        <div class="box">
            {!! Form::open([
                   'url' => 'mandanten/search',
                   'method' => 'POST',
                   'class' => 'horizontal-form' ]) !!}
                <div class="row">
                    <!-- input box-->
                    <div class="col-md-6 col-lg-6"> 
                        <div class="form-group no-margin-bottom">
                            {!! ViewHelper::setInput('search','',old('search'),'', 
                                   trans('mandantenForm.search') , true  ) !!}
                        </div>   
                    </div><!--End input box-->
                    <!-- input box-->
      
                    <div class="col-md-6 col-lg-6"> 
                        <div class="form-group label-form-group  no-margin-bottom">
                                {!! ViewHelper::setCheckbox('deleted_users','', old('deleted_users'),trans('mandantenForm.showDeletedUsers') ) !!}
                                {!! ViewHelper::setCheckbox('deleted_clients','',old('deleted_clients'),trans('mandantenForm.showDeletedClients') ) !!}
                        </div>   
                    </div><!--End input box-->
                    
                        <div class="clearfix"></div>
                    
                    <!-- button div-->    
                    <div class="col-xs-12">
                        <div class="form-group no-margin-bottom custom-input-group-btn">
                           <button type="submit" class="btn btn-primary no-margin-bottom">{{ trans('benutzerForm.search') }}</button>
                            <!--<button type="reset" class="btn btn-info">{{ trans('benutzerForm.reset') }}</button>-->
                        </div>
                    </div><!-- End button div-->    
                </div>           
            </form>
        </div>
    </div>

    <div class="clearfix"><br></div>
  
    @if( !empty($mandants)  ) 
        
        @if( !empty($search) && $search == true )
            <h2 class="title">Suchergebnisse</h2>
        @else
            <h2 class="title">Übersicht</h2>
        @endif
        
        <div class="panel-group">
            
            @foreach( $mandants as $mandant)
            
                {{-- @if(count($mandant->mandantUsers) > 0) --}}
                
                <div class="panel panel-primary" id="panelMandant{{$mandant->id}}">
                    
                    <div class="panel-heading">
                        <h4 class="panel-title col-xs-12">
                                <a data-toggle="collapse" data-target="#collapseMandant{{$mandant->id}}" class="collapsed" 
                                   href="#collapseMandant{{$mandant->id}}">
                                  ({{$mandant->mandant_number}}) {{$mandant->kurzname}}
                                </a>
                            
                        </h4>
                        
                            <span class="panel-options col-xs-12">
                                     <span class="panel-title">
                                          {!! ViewHelper::showUserCount($mandant->usersActive, $mandant->usersInactive) !!}
                                     </span>
                                     <span class="pull-right">
                                    {!! Form::open(['action' => 'MandantController@mandantActivate', 
                                    'method'=>'PATCH']) !!}
                                        <input type="hidden" name="mandant_id" value="{{ $mandant->id }}">
                                        @if($mandant->active)
                                            <button class="btn btn-primary" type="submit" name="active" value="1"></span>Aktiv</button>
                                        @else
                                            <button class="btn btn-danger" type="submit" name="active" value="0"></span>Inaktiv</button>
                                        @endif
                                    {!! Form::close() !!}
                                    
                                    {!! Form::open(['route'=>['mandanten.destroy', 'id'=> $mandant->id], 'method'=>'DELETE']) !!}
                                        <button type="submit" class="btn btn-primary delete-prompt">Entfernen</button>
                                    {!! Form::close() !!}
                                    
                                    <a href="{{ url('/mandanten/'. $mandant->id. '/edit') }}" class="btn btn-primary no-arrow"> Bearbeiten </a> 
                                    </span>
                            </span>
                    </div>
                   
                    <div id="collapseMandant{{$mandant->id}}" class="panel-collapse collapse  
                    @if(Session::has('mandantChanged'))
                        @if( Session::get('mandantChanged') == $mandant->id )
                            in
                        @endif
                    @endif">
                        <div class="panel-body">
                             @if(Session::has('mandantChanged'))
                                @if( Session::get('mandantChanged') == $mandant->id )
                                    <input type="hidden" class="scrollTo" value="#panelMandant{{ $mandant->id }}" />
                                @endif
                            @endif
                            
                            
                            
                            <table class="table data-table">
                            <thead>
                                <th>Name</th>
                                <th class="col-md-8 no-sort">Rollen</th>
                                <th class="no-sort">Mandanten</th>
                                <th class="text-center no-sort">Optionen</th>
                            </thead>
                            <tbody>
                            
                                @if(count($mandant->mandantUsers) > 0)
                                    
                                    @foreach( $mandant->mandantUsers as $mandantUser )
                                        <tr>
                                            <td class="valign">{{ $mandantUser->user->first_name ." ". $mandantUser->user->last_name }} </td>
                                            <td class="col-md-8 valign">
                                                
                                                @foreach( $roles as $role)
                                                    @foreach( $mandantUser->mandantUserRoles as $mandantUserRole)
                                                        @if($mandantUserRole->role_id == $role->id)
                                                            {{ $role->name }};
                                                        @endif
                                                    @endforeach
                                                @endforeach

                                            </td>
                                            <td class="text-center valign">{{ count($mandantUser->user->countMandants) }}</td>
                                            <td class="valign table-options text-center">
                                                {!! Form::open(['action' => 'UserController@userActivate', 'method'=>'PATCH']) !!}
                                                    <input type="hidden" name="user_id" value="{{ $mandantUser->user->id }}">
                                                    <input type="hidden" name="mandant_id" value="{{ $mandant->id }}">
                                                    @if($mandantUser->user->active)
                                                        <button class="btn btn-xs btn-success" type="submit" name="active" value="1"></span>Aktiv</button><br>
                                                    @else
                                                        <button class="btn btn-xs btn-danger" type="submit" name="active" value="0"></span>Inaktiv</button><br>
                                                    @endif
                                                {!! Form::close() !!}
                                                
                                                {{-- Form::open(['route'=>['benutzer.destroy', 'id'=> $mandantUser->user->id], 'method'=>'DELETE']) --}}
                                                {!! Form::open(['action' => 'MandantController@destroyMandantUser', 'method'=>'POST']) !!}
                                                    <input type="hidden" name="user_id" value="{{ $mandantUser->user->id }}">
                                                    <input type="hidden" name="mandant_id" value="{{ $mandant->id }}">
                                                    <button type="submit" class="btn btn-xs btn-warning delete-prompt">Entfernen</button><br>
                                                {!! Form::close() !!}
                                                
                                                <a href="{{route('benutzer.edit', ['id'=> $mandantUser->user->id])}}" class="btn btn-xs btn-primary">Bearbeiten</a>
                                            </td>
                                        </tr>
                                        
                                    @endforeach
                                    
                                    
                                @else
                                    <tr><td colspan="4"> Keine Daten vorhanden. </td></tr>
                                @endif
                            
                            </tbody>
                            </table>

                        </div>
                    </div>
                    
                </div>
                
                {{-- @endif --}}
                
            @endforeach
            
        </div>
        
    @endif
    
    
    @if(!empty($unassignedUsers))
    
        <div class="panel-group">
                
            <div class="panel panel-primary" id="noMandant">
                <div class="panel-heading">
                     <h4 class="panel-title col-xs-12">
                         <a data-toggle="collapse" data-target="#collapseNoMandant" class="collapsed" href="#collapseNoMandant">
                            Kein Mandant
                         </a>
                     </h4>
                      <span class="panel-options col-xs-12">
                            <span class="panel-title">
                                {!! ViewHelper::showUserCount($unassignedActiveUsers, $unassignedInactiveUsers) !!}
                            </span>
                       </span>             
                </div>
                <div id="collapseNoMandant" class="panel-collapse collapse ">
                    <div class="panel-body">
                        
                            @if(count($unassignedUsers) > 0)
                                    <table class="table data-table">
                                    <thead>
                                        <th class="col-md-10">Name</th>
                                        <th class="col-md-2 text-center no-sort">Optionen</th>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach( $unassignedUsers as $unassignedUser )
                                             {{-- @if( $mandantUser->deleted_at == null ) --}}
                                                <tr>
                                                    <td class="valign">{{ $unassignedUser->first_name ." ". $unassignedUser->last_name }} </td>
                                                    <td class="valign table-options text-center">
                                                        {!! Form::open(['action' => 'UserController@userActivate', 'method'=>'PATCH']) !!}
                                                            <input type="hidden" name="user_id" value="{{ $unassignedUser->id }}">
                                                            
                                                            @if($unassignedUser->active)
                                                                <button class="btn btn-xs btn-success" type="submit" name="active" value="1"></span>Aktiv</button><br>
                                                            @else
                                                                <button class="btn btn-xs btn-danger" type="submit" name="active" value="0"></span>Inaktiv</button><br>
                                                            @endif
                                                        {!! Form::close() !!}
                                                        
                                                        {!! Form::open(['route'=>['benutzer.destroy', 'id'=> $unassignedUser->id], 'method'=>'DELETE']) !!}
                                                            <button type="submit" class="btn btn-xs btn-warning">Entfernen</button><br>
                                                        {!! Form::close() !!}
                                                        <a href="{{route('benutzer.edit', ['id'=> $unassignedUser->id])}}" class="btn btn-xs btn-primary">Bearbeiten</a>
                                                    </td>
                                                </tr>
                                            {{-- @endif --}}
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        
                    </div>
                </div>
            </div>
                
        </div>
        
    @endif
    
    
      
    
@stop