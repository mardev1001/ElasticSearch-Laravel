{{-- BENUTZER CREATE --}}

@extends('master')

@section('content')

<h1 class="text-primary">{{ trans('benutzerForm.user') }} {{ trans('benutzerForm.add') }}  </h1>

<fieldset class="form-group">
    
    {!! Form::open(['route' => 'benutzer.store']) !!}
    
    <h4>{{ trans('benutzerForm.baseInfo') }}</h4>
    
    <div class="row">

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('username', '', old('username'), trans('benutzerForm.username'), trans('benutzerForm.username'), true) !!}
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('password', '', old('password'), trans('benutzerForm.password'), trans('benutzerForm.password'), true, 'password') !!}
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('password_repeat', '', old('password_repeat'), trans('benutzerForm.password_repeat'), trans('benutzerForm.password_repeat'), true, 'password') !!}
            </div>   
        </div><!--End input box-->
    
    </div>
    
    <div class="row">
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                <label class="control-label">{{trans('benutzerForm.title')}}</label>
                <select name="title" class="form-control elect">
                    <option value="Frau">Frau</option>
                    <option value="Herr">Herr</option>
                </select>
                
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('first_name', '', old('first_name'), trans('benutzerForm.first_name'), trans('benutzerForm.first_name'), true) !!}
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('last_name', '', old('last_name'), trans('benutzerForm.last_name'), trans('benutzerForm.last_name'), true) !!}
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('birthday', '', old('birthday'), trans('benutzerForm.birthday'), trans('benutzerForm.birthday'), false, 'text', ['datetimepicker']) !!}
            </div>   
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('short_name', '', old('short_name'), trans('benutzerForm.short_name'), trans('benutzerForm.short_name'), false) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('username_sso', '', old('username_sso'), trans('benutzerForm.username_sso'), trans('benutzerForm.username_sso'), false) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('email', '', old('email'), trans('benutzerForm.email'), trans('benutzerForm.email'), true, 'email') !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setCheckbox('email_reciever', '', old('email_reciever'), trans('benutzerForm.email_reciever')) !!}
            </div>   
        </div><!--End input box-->
        
    </div>
    
    <div class="row">
        <!-- input box-->
        <div class="col-lg-2"> 
            <div class="form-group">
                <label>{{ trans('benutzerForm.picture') }}</label>
                <input type="file" id="picture" name="picture" /><br/>
                
                @if(isset($user->picture))
                    @if($user->picture)
                    <img class="img-responsive" id="image-preview" src="http://placehold.it/150x150"/>
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
            <button class="btn btn-white" type="reset">{{ trans('benutzerForm.reset') }}</button>
            <button class="btn btn-primary" type="submit">{{ trans('benutzerForm.save') }}</button>
        </div>
    </div>
    
    {!! Form::close() !!}
    
</fieldset>


<div class="clearfix"></div> <br>

@stop