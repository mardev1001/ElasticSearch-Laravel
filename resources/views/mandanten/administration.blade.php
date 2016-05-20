@extends('master')
    @section('content')
    <h1 class="text-primary">
        {{  ucfirst( trans('controller.administration') ) }}
    </h1>
    {!! Form::open([
           'url' => 'mandanten/search',
           'method' => 'POST',
           'class' => 'horizontal-form' ]) !!}
                   
        <!-- input box-->
        <div class="col-lg-6"> 
            <div class="form-group">
                {!! ViewHelper::setInput('search','',old('search'),trans('mandantenForm.search') , 
                       trans('mandantenForm.search') , true  ) !!}
            </div>   
        </div><!--End input box-->
        <!-- input box-->
        <div class="col-lg-6"> 
            <div class="form-group">
                <br>
                {!! ViewHelper::setCheckbox('deleted_users','', old('deleted_users'),trans('mandantenForm.showDeletedUsers') ) !!}
                {!! ViewHelper::setCheckbox('deleted_clients','',old('deleted_clients'),trans('mandantenForm.showDeletedClients') ) !!}
            </div>   
        </div><!--End input box-->
        
            <div class="clearfix"></div>
        
        <!-- button div-->    
        <div class="col-md-3">
            <div class="form-wrapper">
                <button type="submit" class="btn btn-primary">{{ trans('benutzerForm.search') }}</button>
                <button type="reset" class="btn btn-info">{{ trans('benutzerForm.reset') }}</button>
            </div>
        </div><!-- End button div-->    
           
    </form>


    <div class="clearfix"></div>
  
    @if( !empty($mandants)  ) 
        
        @if( !empty($search) && $search == true )
            <h2>Suchergebnisse</h2>
        @else
            <h2>Übersicht</h2>
        @endif
        
        <div class="panel-group">
            @foreach( $mandants as $mandant)
                <div class="panel panel-primary" id="panelMandant{{$mandant->id}}">
                    <div class="panel-heading">
                         <h4 class="panel-title">
                    <a data-toggle="collapse" data-target="#collapseMandant{{$mandant->id}}" class="collapsed" 
                       href="#collapseMandant{{$mandant->id}}">
                      {{$mandant->name}} [{{ count($mandant->mandantUsers) }} Benutzer]
                    </a>
                    <span class="pull-right">
                        <a href="/mandanten/{{$mandant->id}}/edit" class="btn btn-default no-arrow"> bearbeiten </a> 
                        <button class="btn btn-default"> löchen </button>
                        <button class="btn btn-default"> aktiv </button>
                    </span>
                  </h4>
            
                    </div>
                    <div id="collapseMandant{{$mandant->id}}" class="panel-collapse collapse ">
                        <div class="panel-body">
                            @if(count($mandant->mandantUsers) > 0)
                                    <table class="table table-hover">
                                    <thead>
                                        <th>Name</th>
                                        <th class="col-md-8">Rollen</th>
                                        <th>Mandanten</th>
                                        <th class="text-center">Optionen</th>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach( $mandant->mandantUsers as $mandantUser )
                                            @if( $mandantUser->deleted_at == null )
                                                <tr>
                                                <td class="valign">{{ $mandantUser->user->first_name ." ". $mandantUser->user->last_name }} </td>
                                                <td class="col-md-8 valign">
                                                    <select disabled="true" name="role_id" class="form-control select col-md-8" data-placeholder="{{ trans('benutzerForm.roles') }}" multiple>
                                                        <option value=""></option>
                                                        @foreach( $roles as $role)
                                                            <option value="{{$role->id}}"
                                                            {!! ViewHelper::setMultipleSelect($mandantUser->mandantUserRoles, $role->id, 'role_id') !!}
                                                            >{{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="text-center valign">{{ count($mandantUser->user->countMandants) }}</td>
                                                <td class="valign table-options text-center">
                                                    @if($mandantUser->user->active)
                                                        <button class="btn btn-xs btn-success"></span>Aktiv</button><br>
                                                    @else
                                                        <button class="btn btn-xs btn-danger"></span>Inaktiv</button><br>
                                                    @endif
                                                    
                                                    {!! Form::open(['route'=>['benutzer.destroy', 'id'=> $mandantUser->user->id], 'method'=>'DELETE']) !!}
                                                        <button type="submit" class="btn btn-xs btn-warning">Entfernen</button><br>
                                                    {!! Form::close() !!}
                                                    <a href="{{route('benutzer.edit', ['id'=> $mandantUser->user->id])}}" class="btn btn-xs btn-primary">Bearbeiten</a>
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    
    
    @if(!empty($unassignedUsers))
    
        <div class="panel-group">
                
            <div class="panel panel-primary" id="noMandant">
                <div class="panel-heading">
                     <h4 class="panel-title">
                <a data-toggle="collapse" data-target="#collapseNoMandant" class="collapsed" href="#collapseNoMandant">
                    Kein Mandant [{{ count($unassignedUsers) }} Benutzer]
                </a>
              </h4>
        
                </div>
                <div id="collapseNoMandant" class="panel-collapse collapse ">
                    <div class="panel-body">
                        
                            @if(count($unassignedUsers) > 0)
                                    <table class="table table-hover">
                                    <thead>
                                        <th>Name</th>
                                        <th class="text-center">Optionen</th>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach( $unassignedUsers as $unassignedUser )
                                            @if( $mandantUser->deleted_at == null )
                                                <tr>
                                                    <td class="valign">{{ $unassignedUser->first_name ." ". $unassignedUser->last_name }} </td>
                                                    <td class="valign table-options text-center">
                                                        @if($unassignedUser->active)
                                                            <button class="btn btn-xs btn-success"></span>Aktiv</button><br>
                                                        @else
                                                            <button class="btn btn-xs btn-danger"></span>Inaktiv</button><br>
                                                        @endif
                                                        
                                                        {!! Form::open(['route'=>['benutzer.destroy', 'id'=> $unassignedUser->id], 'method'=>'DELETE']) !!}
                                                            <button type="submit" class="btn btn-xs btn-warning">Entfernen</button><br>
                                                        {!! Form::close() !!}
                                                        <a href="{{route('benutzer.edit', ['id'=> $unassignedUser->id])}}" class="btn btn-xs btn-primary">Bearbeiten</a>
                                                    </td>
                                                </tr>
                                            @endif
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