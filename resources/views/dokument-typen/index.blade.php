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
                
                @foreach($documentTypes as $documentType)
                
                    <tr>
                        <td>
                            
                            {!! Form::open(['route' => ['dokument-typen.update', 'dokument_typen' => $documentType->id], 'method' => 'PATCH']) !!}
                            
                            <div class="row">
                                
                                <div class="col-lg-3"> 
                                    <div class="form-group">
                                        <label>{{ trans('dokumentTypenForm.name') }} <i class="fa fa-asterisk text-info"></i></label>
                                        <input type="text" name="name" class="form-control" placeholder="{{ trans('dokumentTypenForm.name') }}" value="{{ $documentType->name }}" required />
                                    </div>
                                </div>
                                
                                <div class="col-lg-2"> 
                                    <strong>{{ trans('dokumentTypenForm.document_art') }}</strong>
                                    <div class="radio no-margin-top">
                                        <label>
                                            <input type="radio" name="document_art" value="0" @if(!$documentType->document_art) checked @endif >
                                            {{ trans('dokumentTypenForm.editor') }}
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="document_art" value="1" @if($documentType->document_art) checked @endif >
                                            {{ trans('dokumentTypenForm.upload') }} {{ trans('dokumentTypenForm.document') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-lg-2">
                                    <strong>{{ trans('dokumentTypenForm.document_role') }}</strong>
                                    <div class="radio no-margin-top">
                                        <label>
                                            <input type="radio" name="document_role" value="0" @if(!$documentType->document_role) checked @endif>
                                            {{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.verfasser') }}
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input type="radio" name="document_role" value="1" @if($documentType->document_role) checked @endif>
                                            {{ trans('dokumentTypenForm.rundschreiben') }} {{ trans('dokumentTypenForm.verfasser') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-lg-2">
                                    <br>
                                    <div class="checkbox no-margin-top">
                                        <label>
                                            <input type="checkbox" name="read_required" @if($documentType->read_required) checked @endif>
                                            {{ trans('dokumentTypenForm.read_required') }}
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="allow_comments" @if($documentType->allow_comments) checked @endif>
                                            {{ trans('dokumentTypenForm.allow_comments') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-lg-3 table-options text-right">
                                    <br> 
                                    
                                    @if($documentType->active)
                                        <button class="btn btn-success" type="submit" name="activate" value="1"> {{ trans('dokumentTypenForm.active') }} </button>
                                    @else
                                        <button class="btn btn-danger" type="submit" name="activate" value="0"> {{ trans('dokumentTypenForm.inactive') }} </button>
                                    @endif
                                    
                                    <button class="btn btn-primary" type="submit" name="save" value="1"> {{ trans('dokumentTypenForm.save') }} </button>
                                </div>
                                
                            </div>
                            
                            {!! Form::close() !!}
                            
                        </td>
                    </tr>
                
                @endforeach
                
            </table>
            
        </div>
    </div>
    
</fieldset>

<div class="clearfix"></div> <br>

@stop
