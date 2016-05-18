{{-- BENUTZER EDIT --}}

@extends('master')

@section('content')

<h1 class="text-primary">{{ trans('benutzerForm.user') }} {{ trans('benutzerForm.edit') }}  </h1>

<fieldset class="form-group">
    
    {!! Form::open(['route' => ['benutzer.update', $user->id], 'method'=>'PATCH', 'enctype' => 'multipart/form-data']) !!}
    
    <h4>{{ trans('benutzerForm.baseInfo') }}</h4>
    
    <div class="row">

        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('username', $user, old('username'), trans('benutzerForm.username'), trans('benutzerForm.username'), true) !!}
            </div>   
        </div>
        
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('password', '', '', trans('benutzerForm.password'), trans('benutzerForm.password'), false, 'password') !!}
            </div>   
        </div>
        
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('password_repeat', '', '', trans('benutzerForm.password_repeat'), trans('benutzerForm.password_repeat'), false, 'password') !!}
            </div>   
        </div>
        
        <div class="col-lg-3"> 
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="active" @if($user->active) checked @endif >
                        {{trans('benutzerForm.active')}}
                    </label>
                </div>
            </div>   
        </div>
        
    </div>
    
    <div class="row">
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                <label class="control-label">{{trans('benutzerForm.title')}}</label>
                <select name="title" class="form-control select">
                    <option value="Frau">Frau</option>
                    <option value="Herr">Herr</option>
                </select>
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('first_name', $user, old('first_name'), trans('benutzerForm.first_name'), trans('benutzerForm.first_name'), true) !!}
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('last_name', $user, old('last_name'), trans('benutzerForm.last_name'), trans('benutzerForm.last_name'), true) !!}
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('birthday', $user, old('birthday'), trans('benutzerForm.birthday'), trans('benutzerForm.birthday'), false, 'text', ['datetimepicker']) !!}
            </div>   
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('short_name', $user, old('short_name'), trans('benutzerForm.short_name'), trans('benutzerForm.short_name'), false) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('username_sso', $user, old('username_sso'), trans('benutzerForm.username_sso'), trans('benutzerForm.username_sso'), false) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('email', $user, old('email'), trans('benutzerForm.email'), trans('benutzerForm.email'), true, 'email') !!}
            </div>   
        </div><!--End input box-->
        
        <div class="col-lg-3"> 
            <div class="form-group">
                <div class="checkbox">
                    <br>
                    <label>
                        <input type="checkbox" name="email_reciever" @if($user->email_reciever) checked @endif >
                        {{trans('benutzerForm.email_reciever')}}
                    </label>
                </div>
            </div>   
        </div>
        
    </div>
    
    <div class="row">
        
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setInput('active_from', $user, old('active_from'), trans('benutzerForm.active_from'), trans('benutzerForm.active_from'), false, 'text', ['datetimepicker']) !!}
            </div>   
        </div>
        
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setInput('active_to', $user, old('active_to'), trans('benutzerForm.active_to'), trans('benutzerForm.active_to'), false, 'text', ['datetimepicker']) !!}
            </div>   
        </div>
        
    </div>
    
    <div class="row">
        <!-- input box-->
        <div class="col-lg-2"> 
            <div class="form-group">
                <label>{{ trans('benutzerForm.picture') }}</label>
                <input type="file" id="picture" name="picture" /><br/>
                
                @if(isset($user->picture))
                    @if($user->picture)
                    <img class="img-responsive" id="image-preview" src="{{url('/files/pictures/users/'. $user->picture)}}"/>
                    @endif
                @else
                    <img class="img-responsive" id="image-preview" src="{{url('/img/user-default.png')}}"/>
                @endif
            </div>   
        </div><!--End input box-->

    </div>
    
    <div class="clearfix"></div> <br>
    
    <div class="row">
        <div class="col-lg-6">
            <button class="btn btn-primary" type="submit">{{ trans('benutzerForm.save') }}</button>
        </div>
    </div>
    
    {!! Form::close() !!}
    
    <div class="clearfix"></div> <br>
    
</fieldset> 



<fieldset class="form-group">
    
    <h4>{{ trans('benutzerForm.user') }} {{ trans('benutzerForm.roleTransfer') }}</h4>
    
    <div class="row">
        <div class="col-lg-3">
            <div class="form-group">
               {!! ViewHelper::setSelect(null, 'user_id', $user, old('user_id'), trans('benutzerForm.user'), trans('benutzerForm.user'), true) !!}
            </div>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="col-lg-3">
            <button class="btn btn-primary">{{ trans('benutzerForm.roleTransfer') }}</button>
        </div>
        
        <div class="clearfix"></div> <br>
        
    </div>
    
</fieldset>
    
<fieldset class="form-group">
    
    <!--<h4>{{ trans('benutzerForm.mandant') }}/{{ trans('benutzerForm.roles') }} {{ trans('benutzerForm.assignment') }}</h4>-->
    <h4>{{ trans('benutzerForm.roles') }} {{ trans('benutzerForm.assignment') }}</h4>
     
    <div class="row inline">
        <div class="col-lg-3">
            <div class="form-group">
               {!! ViewHelper::setSelect(null, 'mandant_id', $user, old('mandant_id'), trans('benutzerForm.mandant'), trans('benutzerForm.mandant'), true, [], [], []) !!}
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
               {!! ViewHelper::setSelect(null, 'role_id', $user, old('role_id'), trans('benutzerForm.role'), trans('benutzerForm.role'), true, [], [], ['multiple']) !!}
            </div>
        </div>
        
        <div class="col-lg-3 vertical-center">
             <div class="form-group">
                <label>&nbsp;</label><br>
                <button class="btn btn-primary">{{ ucfirst(trans('benutzerForm.add')) }}</button>
            </div>
        </div>
        
    </div>

    <div class="clearfix"></div>

    <h4>{{ trans('benutzerForm.roles') }} {{ trans('benutzerForm.overview') }}</h4>
     
    <div class="row">
        <div class="col-lg-12">
            <table class="table">
                <tr>
                    <th class="col-xs-4 col-md-5">
                        {{ trans('benutzerForm.mandants') }}
                    </th>
                    <th class="col-xs-4 col-md-5">
                        {{ trans('benutzerForm.roles') }}
                    </th>    
                    <th class="col-xs-4 col-md-2">{{ trans('benutzerForm.options') }}</th>    
                </tr>
                @for($i=1; $i < 5; $i++)
                <tr>
                    <td>
                        Mandant {{ $i }}
                    </td>
                    <td>
                        <select name="role_id" class="form-control select" data-placeholder="{{ trans('benutzerForm.roles') }}" multiple>
                            <option value="1">Rolle 1</option>
                            <option value="2">Rolle 2</option>
                            <option value="3">Rolle 3</option>
                            <option value="4">Rolle 4</option>
                        </select>
                    </td>
                    <td class="table-options">
                        <button class="btn btn-success" type="submit">{{ trans('benutzerForm.active') }}</button>
                        <button class="btn btn-primary" type="submit">{{ trans('benutzerForm.save') }}</button>
                    </td>
                </tr>
                @endfor
            </table>
        </div>
    </div>
    
</fieldset>

<div class="clearfix"></div> <br>

@stop