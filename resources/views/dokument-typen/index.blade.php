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
    <div class="box-wrapper">
        <h4 class="title">{{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.type') }} {{ trans('dokumentTypenForm.add') }}</h4>
        
        {!! Form::open(['route' => 'dokument-typen.store']) !!}
        
         <div class="box">
              <div class="box-header">
                  <div class="row">
                    <div class="col-lg-3"><strong>{{ trans('dokumentTypenForm.name') }}*</strong></div>
                    <div class="col-lg-2"><strong>{{ trans('dokumentTypenForm.document_art') }}</strong></div>
                    <div class="col-lg-3"><strong>{{ trans('dokumentTypenForm.document_role') }}</strong></div>
                    <div class="col-lg-2"></div>
                    <div class="col-lg-3"></div>
                </div>
             </div>
            <div class="row">
                <!-- input box-->
                <div class="col-lg-3"> 
                    <div class="form-group">
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="{{ trans('dokumentTypenForm.name') }}*" value="" required />
                        </div>
                        <!--{!! ViewHelper::setInput('name', '', old('name'), trans('dokumentTypenForm.name'), trans('dokumentTypenForm.name'), true) !!} -->
                    </div>
                </div><!--End input box-->
                <div class="col-lg-2"> 
                    <div class="radio no-margin-top">
                        <label><input type="radio" name="document_art" value="0" checked>{{ trans('dokumentTypenForm.editor') }}</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="document_art" value="1">{{ trans('dokumentTypenForm.upload') }} {{ trans('dokumentTypenForm.document') }}</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="radio no-margin-top">
                        <label><input type="radio" name="document_role" value="0" checked>{{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.verfasser') }}</label>
                    </div>
                    <div class="radio">
                        <label><input type="radio" name="document_role" value="1">{{ trans('dokumentTypenForm.rundschreiben') }} {{ trans('dokumentTypenForm.verfasser') }}</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="checkbox no-margin-top">
                       <input type="checkbox" name="read_required" id="read_required-0"><label for="read_required-0">{{ trans('dokumentTypenForm.read_required') }}</label>
                    </div>
                    <div class="checkbox">
                        <input type="checkbox" name="allow_comments" id="allow_comments-0"><label for="allow_comments-0">{{ trans('dokumentTypenForm.allow_comments') }}</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <br> <button class="btn btn-primary pull-right">{{ trans('dokumentTypenForm.add') }} </button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</fieldset>


<fieldset class="form-group">
    
    <h4 class="title">{{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.types') }} {{ trans('dokumentTypenForm.overview') }}</h4>
    <br>
    <div class="box-wrapper">
        <div class="row">
            <div class="col-md-12">
                <h4 class="title"> {{ trans('dokumentTypenForm.document') }} {{ trans('dokumentTypenForm.types') }}</h4>
                <div class="box">
                    <table class="table">
                        <tr>
                            <th class="col-lg-3"><strong>{{ trans('dokumentTypenForm.name') }}</strong></th>
                            <th class="col-lg-2"><strong>{{ trans('dokumentTypenForm.document_art') }}</strong></th>
                            <th class="col-lg-3"><strong>{{ trans('dokumentTypenForm.document_role') }}</strong></th>
                            <th class="col-lg-3"></th>
                            <th class="col-lg-1"></th>
                        </tr>
                        
                        @foreach($documentTypes as $documentType)
                        
                            {!! Form::open(['route' => ['dokument-typen.update', 'dokument_typen' => $documentType->id], 'method' => 'PATCH']) !!}
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <!--<label>{{ trans('dokumentTypenForm.name') }} <i class="fa fa-asterisk text-info"></i></label>-->
                                        <input type="text" name="name" class="form-control" placeholder="{{ trans('dokumentTypenForm.name') }}" value="{{ $documentType->name }}" required />
                                    </div>
                                </td>   
                                <td>
                                    <div class=""> 
                                        @if($documentType->document_art)    
                                            {{ trans('dokumentTypenForm.upload') }} {{ trans('dokumentTypenForm.document') }}
                                        @else
                                            {{ trans('dokumentTypenForm.editor') }}
                                        @endif
                                
                                    </div>
                                </td>
                                <td>     
                                    <div class="">
                                        <div class="radio">
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
                                 </td>  
                                 <td>
                                    <div class="">
                                        
                                        <div class="checkbox">
                                            <input type="checkbox" name="read_required" id="read_required-{{$documentType->id}}" @if($documentType->read_required) checked @endif>
                                            <label for="read_required-{{$documentType->id}}">{{ trans('dokumentTypenForm.read_required') }}</label>
                                        </div>
                                        <div class="checkbox">
                                            <input type="checkbox" name="allow_comments" id="allow_comments-{{$documentType->id}}" @if($documentType->allow_comments) checked @endif>
                                            <label for="allow_comments-{{$documentType->id}}">{{ trans('dokumentTypenForm.allow_comments') }}</label>
                                        </div>
                                    </div>
                                 </td> 
                                 <td>
                                    <div class=" table-options text-right">
                                        @if($documentType->active)
                                            <button class="btn btn-success" type="submit" name="activate" value="1"> {{ trans('dokumentTypenForm.active') }} </button>
                                        @else
                                            <button class="btn btn-danger" type="submit" name="activate" value="0"> {{ trans('dokumentTypenForm.inactive') }} </button>
                                        @endif
                                        
                                        <button class="btn btn-primary" type="submit" name="save" value="1"> {{ trans('dokumentTypenForm.save') }} </button>
                                    </div>
                                </td>
                             
                                    {!! Form::close() !!}
                           
                            </tr>  
                        @endforeach
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</fieldset>

<div class="clearfix"></div> <br>

@stop
