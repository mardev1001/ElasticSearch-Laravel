@extends('master')

@section('page-title')
  Dokumente bearbeiten / anlegen - Grundinfos
@stop


@section('content')
<div class="col-md-12 box-wrapper">
    <div class="box">
        <div class="row">
            <!-- input box-->
            <div class="col-md-4 col-lg-4 "> 
                <div class="form-group">
                    {!! ViewHelper::setInput('name',$data,old('name'),trans('documentForm.documentName') , 
                           trans('documentForm.documentName') , true  ) !!}
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4 "> 
                <div class="form-group">
                    {!! ViewHelper::setInput('search_tags',$data,old('search_tags'),trans('documentForm.searchTags') , 
                           trans('documentForm.searchTags') , true  ) !!} <!-- add later data-role="tagsinput"-->
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                  
                        {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('documentForm.datePublished'), trans('documentForm.datePublished') , true, 'text' , ['datetimepicker']  ) !!}
                    
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    <label class="control-label"> {{ trans('documentForm.user') }} *</label>
                    <select name="user_id" class="form-control select" data-placeholder="{{ strtoupper( trans('documentForm.user') ) }}" required>
                        @foreach( $users as $documentUser )
                            <option value="{{$documentUser->id}}" @if( isset($data->user_id) && $documentUser->id == $data->user_id) selected @endif >
                                {{ $documentUser->last_name }} {{ $documentUser->first_name }}  
                            </option>
                        @endforeach
                    </select>
                </div>
            </div><!--End input box-->
            
            
            <div class="col-md-4 col-lg-4">
                <div class="form-group">
                    <label class="control-label"> {{ trans('documentForm.coauthor') }} </label>
                    <select name="document_coauthor[]" class="form-control select empty-select" data-placeholder="{{ strtoupper( trans('documentForm.coauthor') ) }}">
                        <option value="" @if( Request::is('*/create') ) select @endif >&nbsp;</option>
                        @foreach($users as $mandantUser)
                            <option value="{{$mandantUser->id}}"
                            @if( Request::is('*/edit') )
                                @if(isset($documentCoauthors)) {!! ViewHelper::setMultipleSelect($documentCoauthors, $mandantUser->id, 'user_id') !!} @endif
                            @endif
                            > 
                                {{ $mandantUser->last_name }} {{ $mandantUser->first_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div><!--End input box-->
            
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    <label class="control-label"> {{ ucfirst(trans('documentForm.status')) }} </label>
                    <select name="status" class="form-control select" data-placeholder="{{ ucfirst(trans('documentForm.status')) }}" disabled>
                        <option value="0"></option>
                        @foreach($documentStatus as $status)
                            <option value="{{$status->id}}" 
                            @if( isset($data->document_status_id) )
                                @if($status->id == $data->document_status_id) selected @endif 
                            @else
                                @if($status->id == 1) selected @endif 
                            @endif
                            > 
                                {{ $status->name }}
                            
                            </option>
                        @endforeach
                    </select>
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                        {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('beratungsDokument.datum'), trans('beratungsDokument.datum') , true, 'text' , ['datetimepicker']  ) !!}
                </div>   
            </div>
            <!--End input box-->
            
            
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('date_expired',$data,old('date_expired'), trans('documentForm.dateExpired') , trans('documentForm.dateExpired') , false ,'text', ['datetimepicker'] ) !!}
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-12 col-lg-12">
                <div class="form-group">
                    {!! ViewHelper::setArea('summary',$data,old('summary'),trans('documentForm.summary') ) !!}
                </div>
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    <label class="control-label"> {{ trans('beratungsDokument.dokumentArt') }} *</label>
                    <select name="user_id" class="form-control select" data-placeholder="{{ strtoupper( trans('beratungsDokument.dokumentArt') ) }}" required>
                        @foreach( $documentArts as $documentArt )
                            <option value="{{$documentUser->id}}" @if( isset($data->user_id) && $documentArt->id == $data->user_id) selected @endif >
                                {{ $documentArt->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div><!--End input box-->
        </div>
    </div>
</div>

@stop