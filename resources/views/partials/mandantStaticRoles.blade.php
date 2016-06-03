{{ Form::open(['route' => ['mandant.internal-roles', $data->id], 'method'=>'POST', 'class' => 'add-internal-role']) }}

    <div class="col-lg-4"> 
        <div class="form-group">
            <label class="control-label">{{ trans('mandantenForm.role') }} <i class="fa fa-asterisk text-info"></i></label>
            <select name="role_id" class="form-control select" data-placeholder="{{ trans('mandantenForm.role') }}" required >
                <option></option>
                <option value="1">Lohn</option>
                <option value="2">EDV</option>
                <option value="3">Vertrieb</option>
                <option value="4">Finanzbuchaltung</option>
                <option value="5">Umwelt</option>
            </select>
        </div>   
    </div>
    
    <div class="col-lg-4"> 
        <div class="form-group">
            {!! ViewHelper::setUserSelect($mandantUsers,'user_id', $data, old('user_id'), trans('mandantenForm.user'), trans('mandantenForm.user'), true, ['mandant-roles']  ) !!}
        </div>   
    </div>
    
    <div class="col-lg-4">
        <label>&nbsp;</label>
        <div class="form-group">
            <button class="btn btn-primary" data-adder="userRole" action='generate-user-role'>{{ trans('mandantenForm.add') }}</button>
        </div>
    </div>
    
{{ Form::close() }}


@if(count($internalMandantUsers))
    @foreach($internalMandantUsers as $internalUser)
    <div class="clearfix"></div>
    <!-- <form action=""> -->
        <div class="col-lg-4"> 
            <div class="form-group">
                <label class="control-label">{{ trans('mandantenForm.role') }} <i class="fa fa-asterisk text-info"></i></label>
                <select name="role_id" class="form-control select" data-placeholder="{{ trans('mandantenForm.role') }}" required >
                    <option></option>
                    <option @if($internalUser->role_id == 1) selected @endif value="1">Lohn</option>
                    <option @if($internalUser->role_id == 2) selected @endif value="2">EDV</option>
                    <option @if($internalUser->role_id == 3) selected @endif value="3">Vertrieb</option>
                    <option @if($internalUser->role_id == 4) selected @endif value="4">Finanzbuchaltung</option>
                    <option @if($internalUser->role_id == 5) selected @endif value="5">Umwelt</option>
                </select>
            </div>   
        </div>
        
        <div class="col-lg-4"> 
            <div class="form-group">
                <label class="control-label">{{ trans('mandantenForm.user') }} <i class="fa fa-asterisk text-info"></i></label>
                <select name="user_id" class="form-control select" data-placeholder="{{ trans('mandantenForm.user') }}">
                    <option></option>
                    @foreach($mandantUsers as $mandantUser)
                        <option value="{{ $mandantUser->id }}"  @if($internalUser->user_id == $mandantUser->id) selected @endif>
                            {{ $mandantUser->first_name }} {{ $mandantUser->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>   
        </div>
        
        <div class="col-lg-4">
            <label>&nbsp;</label>
            <div class="form-group">
                <button class="btn btn-primary" data-adder="userRole" action='generate-user-role'>{{ trans('mandantenForm.add') }}</button>
            </div>
        </div>
    <!-- </form> -->
    @endforeach
@endif