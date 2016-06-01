{{-- DOKUMENT TYPEN --}}

@extends('master')

@section('content')

<div class="row">
    <div class="col-xs-12 col-md-12 white-bgrnd">
        <div class="fixed-row">
            <div class="fixed-position ">
                <h1 class="page-title">
                   {{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.types') }} {{ trans('dokumentTypenForm.management') }}
                </h1>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>

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
               <input type="checkbox" name="read_required" id="read_required-0"><label for="read_required-0">{{ trans('dokumentTypenForm.read_required') }}</label>
            </div>
            <div class="checkbox">
                <input type="checkbox" name="allow_comments" id="allow_comments-0"><label for="allow_comments-0">{{ trans('dokumentTypenForm.allow_comments') }}</label>
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
                                    <strong>{{ trans('dokumentTypenForm.document_art') }}</strong> <br>
                                    
                                    @if($documentType->document_art)    
                                        {{ trans('dokumentTypenForm.upload') }} {{ trans('dokumentTypenForm.document') }}
                                    @else
                                        {{ trans('dokumentTypenForm.editor') }}
                                    @endif
                            
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
                                        <input type="checkbox" name="read_required" id="read_required-{{$documentType->id}}" @if($documentType->read_required) checked @endif>
                                        <label for="read_required-{{$documentType->id}}">{{ trans('dokumentTypenForm.read_required') }}</label>
                                    </div>
                                    <div class="checkbox">
                                        <input type="checkbox" name="allow_comments" id="allow_comments-{{$documentType->id}}" @if($documentType->allow_comments) checked @endif>
                                        <label for="allow_comments-{{$documentType->id}}">{{ trans('dokumentTypenForm.allow_comments') }}</label>
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
