<input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
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
        {!! ViewHelper::setSelect($mandantUsers,'owner_user_id',$data,old('owner_user_id'),
                trans('documentForm.owner'), trans('documentForm.owner') ) !!}
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
<div class="col-lg-12">
    <div class="form-group">
        {!! ViewHelper::setArea('summary',$data,old('summary'),trans('documentForm.summary') ) !!}
    </div>   
</div><!--End input box-->  


<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('documentForm.datePublished') ) !!}
    </div>   
</div><!--End input box-->


<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setInput('date_expired',$data,old('date_expired'),trans('documentForm.dateExpired') , 
               trans('documentForm.dateExpired') , true ,'text', ['datetimepicker'] ) !!}
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setSelect($isoDocuments,'iso_category_id',$data,old('iso_category_id'),
                trans('documentForm.isoCategory'), trans('documentForm.isoCategory') ) !!}
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setCheckbox('pdf_upload',$data,old('pdf_upload'),trans('documentForm.pdfUpload') ) !!}
    </div>   
</div><!--End input box-->

<div class="clearfix"></div>
