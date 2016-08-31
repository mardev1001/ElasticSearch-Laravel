@extends('master')

@section('page-title')
    {{  ucfirst( trans('controller.wikiCategory') ) }} 
@stop
    @section('bodyClass')
    mandant-administration
    @stop
    @section('content')
    <div class="col-xs-12 box-wrapper">
        <h2 class="title">{{ trans('wiki.newCategory') }} </h2>
        <div class="box">
            {!! Form::open([
                   'url' => 'wiki-kategorie',
                   'method' => 'POST',
                   'class' => 'horizontal-form' ]) !!}
                <div class="row">
                    <!-- input box-->
                    <div class="col-md-6 col-lg-6"> 
                        <div class="form-group no-margin-bottom">
                            {!! ViewHelper::setInput('name','',old('name'),'', 
                                   trans('wiki.newCategory') , true  ) !!}
                        </div>   
                    </div><!--End input box-->
                    <!-- input box-->
      
                   <div class="clearfix"></div>
                    
                    <!-- button div-->    
                    <div class="col-xs-12">
                        <div class="form-group no-margin-bottom custom-input-group-btn">
                           <button type="submit" class="btn btn-primary no-margin-bottom">{{ trans('benutzerForm.save') }}</button>
                            <!--<button type="reset" class="btn btn-info">{{ trans('benutzerForm.reset') }}</button>-->
                        </div>
                    </div><!-- End button div-->    
                </div>           
            </form>
        </div>
    </div>

    <div class="clearfix"><br></div>
  
    @if( !empty($wikiCategories)  ) 
    <div class="col-xs-12 box-wrapper">  
        @if( !empty($search) && $search == true )
            <h2 class="title">Suchergebnisse</h2>
        @else
            <h2 class="title">Wiki Kategorien verwalten</h2>
        @endif
        <div class="box">
            <div class="row">
            
            <table class="table">
                <thead>
                    <th  class="text-center valign col-md-2">Name</th>
                    <th  class="text-center valign col-md-4">Redakteure</th>
                    <th class="text-center valign col-md-3">Rolle</th>
                    <th class="text-center valign">Top Kategorie</th>
                    <th class="text-center valign">Optionen</th>
                </thead>
                <tbody>
                    @if(count($wikiCategories) > 0)
                        @foreach($wikiCategories as $k => $data)
                        <tr>
                                 {!! Form::open([
                                   'url' => 'wiki-kategorie/'.$data->id,
                                   'method' => 'PATCH',
                                   'class' => 'horizontal-form' ]) !!}
                                <td class="text-center valign col-md-2">{{ $data->name }} </td>
                                <td class="text-center valign col-md-4">
                                    <select name="user_id[]" class="form-control select" required multiple data-placeholder="Redakteure">
                                        <option></option>
                                        @foreach($users as $user){
                                           <option value="{{$user->id}}"  
                                            @if( isset($data->wikiCategoryUsers) ) 
                                            {!! ViewHelper::setMultipleSelect($data->wikiCategoryUsers, $user->id, 'user_id') !!} @endif >
                                            {{ $user->first_name }} {{ $user->last_name }}
                                           </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="text-center valign roles-td">
                                    @if( count($data->wikiRoles) == count($roles) )
                                        <select name="role_id[]" class="form-control select" required multiple data-placeholder="Rolle">
                                            <option></option>
                                            <option value="Alle" selected>Alle</option>
                                              @foreach($roles as $role)
                                                <option value="{{$role->id}}" > 
                                                    {{$role->name}}
                                                    </option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select name="role_id[]" class="form-control select" required multiple data-placeholder="Rolle">
                                        <option></option>
                                        <option value="Alle">Alle</option>
                                          @foreach($roles as $role)
                                            <option value="{{$role->id}}"
                                            @if( isset($data->wikiRoles) ) 
                                            {!! ViewHelper::setMultipleSelect($data->wikiRoles, $role->id, 'role_id') !!} @endif > 
                                                {{$role->name}}
                                        @endforeach
                                    </select>
                                    @endif
                                    
                                </td>
                                <td class="text-center valign"> 
                                    {!! ViewHelper::setCheckbox('top_category',$data,old('top_category'),trans('wiki.topCategory'),false,
                                    array(), array(), $k) !!}</td>
                                <td class="valign table-options text-center">
                                    <button class="btn btn-xs btn-primary" type="submit" name="save" value="save"></span>Speichern</button>
                                </form><!--this is a global for closing -->
                                    
                                     <!--<button type="button" name="check_all" class="btn btn-xs btn-primary all-roles">Alle Rollen</button><br>-->
                                     <!--also if you want the functionallity in trigger.js find all-roles trigger-->
                                    
                                    {{ Form::open(['route' => ['wiki-kategorie.destroy', $data->id], 'method' => 'delete']) }}
                                        <button type="submit" name="delete" class="btn btn-xs btn-danger delete-prompt"
                                        data-text=" Wollen Sie diesen Eintrag wirklich löschen?">Entfernen</button><br>
                                    {{ Form::close() }}
                                   
                                </td>
                            </tr>
                            
                        @endforeach
                    @else
                        <tr><td colspan="4"> Keine Daten vorhanden. </td></tr>
                    @endif
                
                </tbody>
            </table>
        </div><!-- end box -->
       
    </div>    
    @endif
    
@stop