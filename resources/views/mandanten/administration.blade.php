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
                {!! ViewHelper::setCheckbox('deleted_users','',old('deleted_users'),trans('mandantenForm.showDeletedUsers') ) !!}
                
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
            <h2>Suche Ausgabe </h2>
         @else
            <h2>Ausgabe Übersicht -trans</h2>
        @endif
        <div class="panel-group" id="accordion">
            @foreach( $mandants as $mandant)
                <div class="panel panel-primary" id="panelMandant{{$mandant->id}}">
                    <div class="panel-heading">
                         <h4 class="panel-title">
                    <a data-toggle="collapse" data-target="#collapseMandant{{$mandant->id}}" class="collapsed" 
                       href="#collapseMandant{{$mandant->id}}">
                      {{$mandant->name}} ( {{ count($mandant->mandantUsers) }} users )
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
                            @if( count($mandant->mandantUsers) > 0 )
                                    <table class="table table-hover">
                                    <thead>
                                        <th>Username</th>
                                        <th class="col-md-8">Roles</th>
                                        <th>Mandanten</th>
                                        <th>Aktiv</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </thead>
                                    <tbody>
                                        @foreach( $mandant->mandantUsers as $mandantUser)
                                            
                                            <tr>
                                               <td class="valign">{{ $mandantUser->user->username }} </td>
                                                <td class="col-md-8">
                                                  <select disabled="true" name="role_id" class="form-control select col-md-8" data-placeholder="{{ trans('benutzerForm.roles') }}" multiple>
                                                    <option value=""></option>
                                                    @foreach( $roles as $role)
                                                        <option value="{{$role->id}}"
                                                        {!! ViewHelper::setMultipleSelect($mandantUser->mandantUserRoles,$role->id) !!}
                                                        >{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                                </td>
                                                <td class="text-center valign">{{ count($mandantUser->user->countMandants) }}</td>
                                                <td class="valign"> @if($mandantUser->user->active == "0") inaktiv @else aktiv @endif </td>
                                                <td class="valign">
                                                    <button class="btn btn-primary"><span class="fa fa-edit"></span> edit</button>
                                                </td>
                                                <td class="valign">
                                                    <button class="btn btn-danger"><span class="fa fa-trash"></span> delete</button>
                                                </td>
                                            </tr>
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
      

    {!! Form::open([
           'url' => 'some action',
           'method' => 'POST',
           'class' => 'horizontal-form']) !!}
    </form>
    
    {!! Form::open([
           'url' => 'some action',
           'method' => 'POST',
           'class' => 'horizontal-form']) !!}
           
         
           
    </form>
    
    
    @stop