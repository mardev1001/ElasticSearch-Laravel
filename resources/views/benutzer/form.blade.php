
<fieldset class="form-group">
    
    <h4>{{ trans('benutzerForm.baseInfo') }}</h4>
    
    <div class="row">

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('username', $data, old('username'), trans('benutzerForm.username'), trans('benutzerForm.username'), true) !!}
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('password', $data, old('password'), trans('benutzerForm.password'), trans('benutzerForm.password'), true, 'password') !!}
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('password_repeat', $data, old('password_repeat'), trans('benutzerForm.password_repeat'), trans('benutzerForm.password_repeat'), true, 'password') !!}
            </div>   
        </div><!--End input box-->
    
    </div>
    
    <div class="row">
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setSelect($gender, 'title', $data, old('title'), trans('benutzerForm.title'), trans('benutzerForm.title'), false) !!}
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('first_name', $data, old('first_name'), trans('benutzerForm.first_name'), trans('benutzerForm.first_name'), true) !!}
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('last_name', $data, old('last_name'), trans('benutzerForm.last_name'), trans('benutzerForm.last_name'), true) !!}
            </div>   
        </div><!--End input box-->

        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('birthday', $data, old('birthday'), trans('benutzerForm.birthday'), trans('benutzerForm.birthday'), false, 'text', ['datetimepicker']) !!}
            </div>   
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('short_name', $data, old('short_name'), trans('benutzerForm.short_name'), trans('benutzerForm.short_name'), false) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('username_sso', $data, old('username_sso'), trans('benutzerForm.username_sso'), trans('benutzerForm.username_sso'), false) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('email', $data, old('email'), trans('benutzerForm.email'), trans('benutzerForm.email'), true, 'email') !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                <br>        
               {!! ViewHelper::setCheckbox('email_reciever', $data, old('email_reciever'), trans('benutzerForm.email_reciever'), trans('benutzerForm.email_reciever'), false) !!}
            </div>   
        </div><!--End input box-->
        
    </div>
    
    <div class="row">
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                <label>{{ trans('benutzerForm.picture') }}</label>
                <input type="file" id="picture" name="picture" /><br/>
                <img class="img-responsive" id="image-preview" src="http://placehold.it/200x200"/>
            </div>   
        </div><!--End input box-->

    </div>
    
</fieldset> 


<fieldset class="form-group">
    
    <h4>{{ trans('benutzerForm.mandant') }}/{{ trans('benutzerForm.roles') }} {{ trans('benutzerForm.assignment') }}</h4>
     
    <div class="row">
        <div class="col-lg-3">
            <div class="form-group">
               {!! ViewHelper::setSelect(null, 'mandant_id', $data, old('mandant_id'), trans('benutzerForm.mandant'), trans('benutzerForm.mandant'), true, [], [], ['multiple']) !!}
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
               {!! ViewHelper::setSelect(null, 'role_id', $data, old('role_id'), trans('benutzerForm.role'), trans('benutzerForm.role'), true, [], [], ['multiple']) !!}
            </div>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="col-lg-3">
            <button class="btn btn-white">{{ trans('benutzerForm.mandant') }}/{{ trans('benutzerForm.roles') }} {{ trans('benutzerForm.add') }}</button>
        </div>
        
        <div class="clearfix"></div> <br>
        
    </div>
        
    <div class="row">
        <div class="col-lg-3">
            <div class="form-group">
               {!! ViewHelper::setSelect(null, 'user_id', $data, old('user_id'), trans('benutzerForm.user'), trans('benutzerForm.user'), true) !!}
            </div>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="col-lg-3">
            <button class="btn btn-white">{{ trans('benutzerForm.roleTransfer') }}</button>
        </div>
        
        <div class="clearfix"></div> <br>
        
    </div>
    
    
</fieldset>
 

<fieldset class="form-group">
    
    <h4>{{ trans('benutzerForm.mandant') }}/{{ trans('benutzerForm.roles') }} {{ trans('benutzerForm.overview') }}</h4>
     
    <div class="row">
        <div class="col-lg-6">
            <table class="table">
                <tr>
                    <th class="col-xs-6 col-md-5">
                        {{ trans('benutzerForm.mandants') }}
                    </th>
                    <th class="col-xs-6 col-md-7">
                        {{ trans('benutzerForm.roles') }}
                    </th>    
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
                </tr>
                @endfor
            </table>
        </div>
    </div>
    
</fieldset>

<div class="clearfix"></div> <br>
   