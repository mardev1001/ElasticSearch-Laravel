<input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
<div class="col-md-12 box-wrapper"> 
    <div class="row">
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group document-type-select">
                {!! ViewHelper::setSelect($documentTypes,'document_type_id',$data,old('document_type_id'),
                        trans('documentForm.documentType'), trans('documentForm.type'),true ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setInput('name',$data,old('name'),trans('documentForm.documentName') , 
                       trans('documentForm.documentName') , true  ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setInput('betreff',$data,old('betreff'),trans('documentForm.subject') , 
                       trans('documentForm.subject') , true  ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setInput('search_tags',$data,old('search_tags'),trans('documentForm.searchTags') , 
                       trans('documentForm.searchTags') , true  ) !!} <!-- add later data-role="tagsinput"-->
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3 iso-category-select"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($isoDocuments,'iso_category_id',$data,old('iso_category_id'),
                        trans('documentForm.isoCategory'), trans('documentForm.isoCategory') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                <label class="control-label"> {{ ucfirst(trans('documentForm.owner')) }} </label>
                <select name="owner_user_id" class="form-control select" data-placeholder="{{ ucfirst(trans('documentForm.owner')) }}">
                    <option value="0"></option>
                    @foreach($mandantUsers as $mandantUser)
                        <option value="{{$mandantUser->id}}" @if(isset($data->owner_user_id)) @if($mandantUser->id == $data->owner_user_id) selected @endif @endif> 
                            {{ $mandantUser->first_name }} {{ $mandantUser->last_name }} 
                        </option>
                    @endforeach
                </select>
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('documentForm.datePublished'), trans('documentForm.datePublished') , false, 'text' , ['datetimepicker']  ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setInput('date_expired',$data,old('date_expired'), trans('documentForm.dateExpired') , trans('documentForm.dateExpired') , true ,'text', ['datetimepicker'] ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3 pdf-checkbox"> 
            <div class="form-group">
                <br>
                {!! ViewHelper::setCheckbox('pdf_upload',$data,old('pdf_upload'),trans('documentForm.pdfUpload') ) !!}
            </div>   
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
        <!-- input box-->
        <div class="col-xs-6"> 
            <div class="form-group">
                <label class="control-label"> {{ trans('documentForm.coauthor') }} </label>
                <select name="document_coauthor[]" class="form-control select" data-placeholder="{{ strtoupper( trans('documentForm.coauthor') ) }}" multiple>
                    <option value="0"></option>
                    @foreach($mandantUsers as $mandantUser)
                        <option value="{{$mandantUser->id}}" @if(isset($documentCoauthor)) {!! ViewHelper::setMultipleSelect($documentCoauthor, $mandantUser->id, 'user_id') !!} @endif > 
                            {{ $mandantUser->first_name }} {{ $mandantUser->last_name }} 
                        </option>
                    @endforeach
                </select>
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('summary',$data,old('summary'),trans('documentForm.summary') ) !!}
            </div>   
        </div><!--End input box-->  
    </div>


<div class="clearfix"></div> <br/>
