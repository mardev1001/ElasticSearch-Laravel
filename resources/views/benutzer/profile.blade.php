{{-- BENUTZER EDIT --}}

@extends('master')

@section('page-title')
{{ trans('benutzerForm.profileTitle') }}
@stop

@section('content')

<fieldset class="form-group">
    
    {!! Form::open(['route' => ['benutzer.update', $user->id], 'method'=>'PATCH', 'enctype' => 'multipart/form-data']) !!}
    <div class="box-wrapper">
        <h4 class="title">{{ trans('benutzerForm.baseInfo') }}</h4>
        <div class="box">
            <div class="row">
        
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('username', $user, old('username'), trans('benutzerForm.username'), trans('benutzerForm.username'), false, $type='text' , array(), array('readonly') ) !!}
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('password', '', '', trans('benutzerForm.password'), trans('benutzerForm.password'), false, 'password') !!}
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('password_repeat', '', '', trans('benutzerForm.password_repeat'), trans('benutzerForm.password_repeat'), false, 'password') !!}
                    </div>   
                </div>
                
                 <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        <label class="control-label">{{trans('benutzerForm.title')}}</label>
                        <select name="title" class="form-control select" placeholder="{{trans('benutzerForm.title')}}">
                            <option value="Frau" @if($user->title == "Frau") selected @endif >Frau</option>
                            <option value="Herr" @if($user->title == "Herr") selected @endif >Herr</option>
                        </select>
                    </div>   
                    
                </div><!--End input box-->
        
                <div class="clearfix visible-lg-block"></div>
        
                <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('first_name', $user, old('first_name'), trans('benutzerForm.first_name'), trans('benutzerForm.first_name'), true) !!}
                    </div>   
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                
                    <div class="form-group">
                       {!! ViewHelper::setInput('last_name', $user, old('last_name'), trans('benutzerForm.last_name'), trans('benutzerForm.last_name'), true) !!}
                    </div>   
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                
                    <div class="form-group">
                       {!! ViewHelper::setInput('birthday', $user, old('birthday'), trans('benutzerForm.birthday'), trans('benutzerForm.birthday'), false, 'text', ['datetimepicker']) !!}
                    </div>   
                </div><!--End input box-->
                
                <!--<div class="clearfix"></div>-->
                
                <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                
                    <div class="form-group">
                       {!! ViewHelper::setInput('short_name', $user, old('short_name'), trans('benutzerForm.short_name'), trans('benutzerForm.short_name'), false) !!}
                    </div>   
                </div><!--End input box-->
                
                <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                       {!! ViewHelper::setInput('username_sso', $user, old('username_sso'), trans('benutzerForm.username_sso'), trans('benutzerForm.username_sso'),  false, $type='text' , array(), array('readonly') ) !!}
                    </div>   
                </div><!--End input box-->
                
                <div class="col-md-4 col-lg-3"> 
                
                    <div class="form-group">
                        <!-- Telefon -->
                        {!! ViewHelper::setInput('phone', $user, old('phone'), trans('benutzerForm.phone'), trans('benutzerForm.phone'), false) !!}
                    </div>
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        <!-- Telefon Mobil-->
                        {!! ViewHelper::setInput('phone_mobile', $user, old('phone_mobile'), trans('benutzerForm.phone_mobile'), trans('benutzerForm.phone_mobile'), false) !!}
                    </div>
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                
                    <div class="form-group">
                        <!-- Kurzwahl -->
                        {!! ViewHelper::setInput('phone_short', $user, old('phone_short'), trans('benutzerForm.phone_short'), trans('benutzerForm.phone_short'), false) !!}
                    </div>
                </div>
                
                <!-- input box-->
                <div class="col-md-4 col-lg-3"> 
                
                    <div class="form-group">
                       {!! ViewHelper::setInput('email', $user, old('email'), trans('benutzerForm.email'), trans('benutzerForm.email'), true, 'email') !!}
                    </div>   
                </div><!--End input box-->
                
                <div class="col-md-4 col-lg-3"> 
                
                    <div class="form-group">
                        <div class="checkbox">
                            {!! ViewHelper::setCheckbox('email_reciever',$user,old('email_reciever'), trans('benutzerForm.email_reciever') ) !!}
                         
                        </div>
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setInput('position', $user, old('position'), trans('benutzerForm.position'), trans('benutzerForm.position'), true) !!}
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setInput('abteilung', $user, old('abteilung'), trans('benutzerForm.abteilung'), trans('benutzerForm.abteilung'), false) !!}
                    </div>   
                </div>
                
                <div class="col-md-4 col-lg-3"> 
                    <div class="form-group">
                        {!! ViewHelper::setInput('informationen', $user, old('informationen'), trans('benutzerForm.informationen'), trans('benutzerForm.informationen'), false) !!}
                    </div>   
                </div>
                
            </div>
            
            <div class="row">
                <!-- input box-->
                <div class="col-md-2 col-lg-2"> 
                    <div class="form-group">
                        <label>{{ trans('benutzerForm.picture') }}</label>
                        <input type="file" id="image-upload" name="picture" /><br/>
                        
                        @if(isset($user->picture))
                            @if($user->picture)
                                <img id="image-preview" class="img-responsive" src="{{ url('/files/pictures/users/'. $user->picture) }}"/>
                            @endif
                        @else
                            <img id="image-preview" class="img-responsive" src="{{ url('/img/user-default.png') }}"/>
                        @endif
                    </div>   
                </div><!--End input box-->
        
            </div>
            
            <div class="clearfix"></div> <br>
            
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <input type="hidden" name="active" value="1">
                    <button class="btn btn-primary no-margin-bottom" type="submit">{{ trans('benutzerForm.save') }}</button>
                </div>
            </div>
        </div><!--end box-->
    </div>
    {!! Form::close() !!}
    
    <div class="clearfix"></div> <br>
    
</fieldset> 

<fieldset>
    
    @if(count($user->mandantUsers))
    
        <div class="box-wrapper">
            <h4 class="title">{{ trans('benutzerForm.roles') }} {{ trans('benutzerForm.overview') }}</h4>
             
             <div class="box">
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <table class="table">
                            <tr>
                                <th class="col-xs-4 col-md-5">
                                    {{ trans('benutzerForm.mandants') }}
                                </th>
                                <th class="col-xs-4 col-md-5">
                                    {{ trans('benutzerForm.roles') }}
                                </th>
                            </tr>
                            @foreach($user->mandantUsersDistinct as $mandantUser)
                            
                                @if($mandantUser->deleted_at == null)
                                    <tr id="mandant-role-{{$mandantUser->id}}">
                                        <td>
                                            ({{ $mandantUser->mandant->mandant_number }}) {{ $mandantUser->mandant->kurzname }}
                                            <input type="hidden" name="mandant_user_id" value="{{$mandantUser->id}}">
                                            <input type="hidden" name="user_id" value="{{$mandantUser->user_id}}">
                                            <input type="hidden" name="mandant_id" value="{{$mandantUser->mandant_id}}">
                                        </td>
                                        <td>
                                            @foreach($mandantUser->mandantUserRoles as $mur)
                                                @if($mur->role->phone_role || $mur->role->mandant_role)
                                                    {{ $mur->role->name }}; 
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endif
                                
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    
    @endif

</fieldset>

@stop