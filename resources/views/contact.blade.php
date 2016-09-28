@extends('master')

@section('page-title-class') Kontakt @stop

@section('page-title') Kontakt @stop

@section('bodyClass') contactPage @stop

@section('content')
<div class="row">
    
    <div class="col-xs-12 col-md-6 ">
        <div class="col-xs-12 box-wrapper">
            <h1 class="title">Bitte wählen Sie Ihren Kontakt:</h1>
             {!! Form::open([
                'method'=>'POST' 
                ]) !!}
            <div class="box">
               
                
                <!-- input box-->
                <div class="col-md-12 col-lg-12"> 
                    <label class="control-label">
                        Neptun user*
                    </label>
                    <div class="form-group">
                        <select name="to_user" class="select form-control" required>
                            <option></option>
                            @foreach($users as $user)
                                <option value="{{$user->id}}">
                                    {{$user->first_name}} {{$user->last_name}}
                                </option>
                            @endforeach
                        </select>
                       
                    </div>   
                </div><!--End input box-->
                
            
                <!-- input box-->
                <div class="col-md-12 col-lg-12 "> 
                    <div class="form-group">       
                        {!! ViewHelper::setInput('subject',$data,old('subject'), trans('contactForm.subject'), trans('contactForm.subject'), true  ) !!}
                    </div>   
                </div><!--End input box-->
                
                <!-- input box-->
                <div class="col-md-12 col-lg-12">
                    <div class="form-group">
                        {!! ViewHelper::setArea('summary',$data,old('summary'),trans('contactForm.message'),trans('contactForm.message'),true ) !!}
                    </div>
                </div><!--End input box-->  
                
                <!-- input box-->
                <div class="col-md-12 col-lg-12">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4 col-lg-4"> 
                      
                                <div class="form-group no-margin-bottom">
                                    {!! ViewHelper::setCheckbox('copy',$data,old('copy'),'Kopie an mich senden' ) !!}
                                </div>
                                  <br>
                            </div><!--End input box-->
                        </div>
                    </div>
                </div><!--End input box-->  
                
                
                     <div class="col-md-12 col-lg-12 "> 
                         <div class="form-group">
                             <button class="btn btn-primary">{{ trans('contactForm.send') }}</button>
                         </div>
                 </div>
                
                <div class="clearfix"></div>
                
            </div><!--end .box-->
            </form>
        </div>
    </div>
    

</div>

@stop
   