{{-- DOKUMENT TYPEN --}}

@extends('master')

@section('content')

<h1 class="text-primary">{{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.types') }} {{ trans('dokumentTypenForm.management') }}</h1>

<fieldset class="form-group">
    
    <h4>{{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.type') }} {{ trans('dokumentTypenForm.add') }}</h4>
    
    {!! Form::open(['route' => 'dokument-typen.store']) !!}
    <div class="row">
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setInput('name', '', old('name'), trans('dokumentTypenForm.name'), trans('dokumentTypenForm.name'), true) !!} 
            </div>
        </div><!--End input box-->
        <div class="col-lg-2"> 
            <strong>{{ trans('dokumentTypenForm.document_art') }}</strong>
            <div class="radio no-margin-top">
                <label><input type="radio" name="document_art" value="0" checked>{{ trans('dokumentTypenForm.editor') }}</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="document_art" value="1">{{ trans('dokumentTypenForm.upload') }} {{ trans('dokumentTypenForm.document') }}</label>
            </div>
        </div>
        <div class="col-lg-2">
            <strong>{{ trans('dokumentTypenForm.document_role') }}</strong>
            <div class="radio no-margin-top">
                <label><input type="radio" name="document_role" value="0" checked>{{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.verfasser') }}</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="document_role" value="1">{{ trans('dokumentTypenForm.rundschreiben') }} {{ trans('dokumentTypenForm.verfasser') }}</label>
            </div>
        </div>
        <div class="col-lg-2">
            <br>
            <div class="checkbox no-margin-top">
                <label><input type="checkbox" name="read_required">{{ trans('dokumentTypenForm.read_required') }}</label>
            </div>
            <div class="checkbox">
                <label><input type="checkbox" name="allow_comments">{{ trans('dokumentTypenForm.allow_comments') }}</label>
            </div>
        </div>
        <div class="col-lg-3">
            <br> <button class="btn btn-primary">{{ trans('dokumentTypenForm.add') }} </button>
        </div>
    </div>
    {!! Form::close() !!}
    
</fieldset>


<fieldset class="form-group">
    
    <h4>{{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.types') }} {{ trans('dokumentTypenForm.overview') }}</h4>
     
    <div class="row">
        <div class="col-md-12">
            
            <table class="table">
                <tr>
                    <th>
                       {{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.types') }}
                    </th>
                </tr>
                
                @for($i=1; $i < 5; $i++)
                
                    <tr>
                        <td>
                            
                            {!! Form::open(['route' => ['dokument-typen.update', $i]]) !!}
                            
                            <div class="row">
                                <!-- input box-->
                                <div class="col-lg-3"> 
                                    <div class="form-group">
                                        {!! ViewHelper::setInput('name', '', old('name'), trans('dokumentTypenForm.name'), trans('dokumentTypenForm.name'), true) !!} 
                                    </div>
                                </div><!--End input box-->
                                <div class="col-lg-2"> 
                                    <strong>{{ trans('dokumentTypenForm.document_art') }}</strong>
                                    <div class="radio no-margin-top">
                                        <label><input type="radio" name="document_art" value="0" checked>{{ trans('dokumentTypenForm.editor') }}</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="document_art" value="1">{{ trans('dokumentTypenForm.upload') }} {{ trans('dokumentTypenForm.document') }}</label>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <strong>{{ trans('dokumentTypenForm.document_role') }}</strong>
                                    <div class="radio no-margin-top">
                                        <label><input type="radio" name="document_role" value="0" checked>{{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.verfasser') }}</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="document_role" value="1">{{ trans('dokumentTypenForm.rundschreiben') }} {{ trans('dokumentTypenForm.verfasser') }}</label>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <br>
                                    <div class="checkbox no-margin-top">
                                        <label><input type="checkbox" name="read_required">{{ trans('dokumentTypenForm.read_required') }}</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="allow_comments">{{ trans('dokumentTypenForm.allow_comments') }}</label>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <br> 
                                    <button class="btn btn-white">{{ trans('dokumentTypenForm.active') }} </button>
                                    <button class="btn btn-primary">{{ trans('dokumentTypenForm.add') }} </button>
                                </div>
                            </div>
                            
                            {!! Form::close() !!}
                            
                        </td>
                    </tr>
                
                @endfor
                
            </table>
            
        </div>
    </div>
    
</fieldset>

<div class="clearfix"></div> <br>

@stop
