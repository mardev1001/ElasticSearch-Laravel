    <div class="box-wrapper">
        <div class="box">
            <div class="row">
            {{ Form::open(['route' => ['mandant.internal-roles-add', $data->id], 'method'=>'POST', 'class' => 'add-internal-role']) }}
            
                <div class="col-md-6 col-lg-4"> 
                    <div class="form-group">
                        
                        <label>{{ trans('mandantenForm.role-zuordnung') }}</label>
                        <select name="role_id" class="form-control select" data-placeholder="Rolle wählen *" required >
                            <option></option>
                            @foreach($roles as $role)
                                <option value="{{$role->id}}">{{$role->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4"> 
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <select name="user_id" class="form-control select" data-placeholder="Mitarbeiter auswählen *">
                            <option></option>
                            @foreach($mandantUsers as $mandantUser)
                                @if($mandantUser->active)
                                    <option value="{{ $mandantUser->id }}" required>
                                        {{ $mandantUser->first_name }} {{ $mandantUser->last_name }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>   
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="form-group custom-input-group-btn">
                        <button class="btn btn-primary" type="submit" name='role-create' value="1">{{ trans('mandantenForm.add') }}</button>
                    </div>
                </div>
                
            {{ Form::close() }}

@if(count($internalMandantUsers))
    @foreach($internalMandantUsers as $internalUser)
    <div class="clearfix"></div>
    
    {{ Form::open(['route' => ['mandant.internal-roles-edit', $data->id], 'method'=>'POST', 'id' => 'internal-role-'.$internalUser->id, 'class' => 'edit-internal-role']) }}
        <div class="col-md-6 col-lg-4"> 
            <div class="form-group">
                <!--<label class="control-label">{{ trans('mandantenForm.role') }}*</label>-->
                <select name="role_id" class="form-control select" data-placeholder="Rolle wählen *" required >
                    <option></option>
                    @foreach($roles as $role)
                        <option @if($internalUser->role_id == $role->id) selected @endif value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                </select>
            </div>   
        </div>
        
        <div class="col-md-6 col-lg-4"> 
            <div class="form-group">
                <!--<label class="control-label">{{ trans('mandantenForm.user') }}*</label>-->
                <select name="user_id" class="form-control select" data-placeholder="Mitarbeiter auswählen *">
                    <option></option>
                    @foreach($mandantUsers as $mandantUser)
                        <option value="{{ $mandantUser->id }}"  @if($internalUser->user_id == $mandantUser->id) selected @endif>
                            @if($mandantUser->active)
                                {{ $mandantUser->first_name }} {{ $mandantUser->last_name }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>   
        </div>
        
        <div class="col-md-6 col-lg-4">
            <div class="form-group">
                <input type="hidden" name="internal_mandant_user_id" value="{{$internalUser->id}}">
                <button class="btn btn-danger delete-prompt" type="submit" name='role-delete' value="1">{{ trans('mandantenForm.remove') }}</button>
                <button class="btn btn-primary" type="submit" name='role-update' value="1">{{ trans('mandantenForm.save') }}</button>
            </div>
        </div>
    {{ Form::close() }}
    
    @endforeach
@endif


<div id="internal-roles" class="clearfix"></div>

   </div></div>
