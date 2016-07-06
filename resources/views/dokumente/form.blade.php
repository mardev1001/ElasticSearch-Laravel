@section('page-title') {{ trans('controller.create') }} @stop

<input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
<div class="col-md-12 box-wrapper"> 
    <div class="box">
        <div class="row">
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group document-type-select">
                    {!! ViewHelper::setSelect($documentTypes,'document_type_id',$data,old('document_type_id'),
                            trans('documentForm.type'), trans('documentForm.type'),true ) !!}
                </div>   
            </div><!--End input box-->
            
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
                    {!! ViewHelper::setInput('name_long',$data,old('name_long'),trans('documentForm.documentNameLong') , 
                           trans('documentForm.documentNameLong')   ) !!}
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
            <div class="col-md-4 col-lg-4 iso-category-select"> 
                <div class="form-group">
                    {!! ViewHelper::setSelect($isoDocuments,'iso_category_id',$data,old('iso_category_id'),
                            trans('documentForm.isoCategory'), trans('documentForm.isoCategory') ) !!}
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4 "> 
                <div class="form-group">
                    <label class="control-label"> {{ ucfirst(trans('documentForm.owner')) }}*</label>
                    <select name="owner_user_id" class="form-control select" data-placeholder="{{ ucfirst(trans('documentForm.owner')) }}">
                        @foreach($mandantUsers as $mandantUser)
                            <option value="{{$mandantUser->id}}" 
                            @if(isset($data->owner_user_id)) 
                                @if($mandantUser->id == $data->owner_user_id) 
                                    selected  
                                @endif 
                            @elseif( isset( Auth::user()->id )  )
                                @if($mandantUser->id ==  Auth::user()->id ) selected @endif 
                            @endif> 
                                {{ $mandantUser->first_name }} {{ $mandantUser->last_name }} 
                            </option>
                        @endforeach
                    </select>
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                        {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('documentForm.datePublished'), trans('documentForm.datePublished') , false, 'text' , ['datetimepicker']  ) !!}
                    
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('date_expired',$data,old('date_expired'), trans('documentForm.dateExpired') , trans('documentForm.dateExpired') , false ,'text', ['datetimepicker'] ) !!}
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    <label class="control-label"> {{ ucfirst(trans('documentForm.status')) }} </label>
                    <select name="status" class="form-control select" data-placeholder="{{ ucfirst(trans('documentForm.status')) }}" disabled>
                        <option value="0"></option>
                        @foreach($documentStatus as $status)
                            <option value="{{$status->id}}"  @if($status->id == 1) selected @endif > 
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                    <label class="control-label"> {{ trans('documentForm.coauthor') }} </label>
                    <select name="document_coauthor[]" class="form-control select" data-placeholder="{{ strtoupper( trans('documentForm.coauthor') ) }}">
                        <option value="0"></option>
                        @foreach($mandantUsers as $mandantUser)
                            <option value="{{$mandantUser->user->id}}" @if(isset($documentCoauthor)) {!! ViewHelper::setMultipleSelect($documentCoauthor, $mandantUser->user->id, 'user_id') !!} @endif > 
                                {{ $mandantUser->user->first_name }} {{ $mandantUser->user->last_name }} 
                            </option>
                        @endforeach
                    </select>
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-md-4 col-lg-4 pdf-checkbox"> 
                <div class="form-group ">
                    {!! ViewHelper::setCheckbox('pdf_upload',$data,old('pdf_upload'),trans('documentForm.pdfUpload') ) !!}
                </div>   
            </div><!--End input box-->
            
            <div class="clearfix"></div>

            <!-- input box-->
            <div class="col-md-12 col-lg-12">
                <div class="form-group">
                    {!! ViewHelper::setArea('summary',$data,old('summary'),trans('documentForm.summary') ) !!}
                </div>   
            </div><!--End input box-->  
        </div>
    </div>


<div class="clearfix"></div> <br/>
