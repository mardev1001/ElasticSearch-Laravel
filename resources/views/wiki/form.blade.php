@section('page-title') {{ trans('controller.wiki') }} @stop

<input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />

<div class="col-md-12 box-wrapper"> 
    <div class="box">
        <div class="row">
            <!-- input box-->
            <div class="col-lg-4"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('wiki.headline'),
                    trans('wiki.headline') ,true  ) !!}
                </div>   
            </div><!--End input box-->
            
            
            <!-- input box-->
            <div class="col-lg-4"> 
                <div class="form-group">
                    <label class="control-label"> {{ ucfirst(trans('documentForm.status')) }} </label>
                    <select name="status_id" class="form-control select" data-placeholder="{{ ucfirst(trans('documentForm.status')) }}" disabled>
                        @foreach($documentStatus as $status)
                            <option value="{{$status->id}}" @if($status->id == 1) selected @endif> 
                                {{ $status->name }}
                            </option>
                        @endforeach
                    </select>
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-lg-4"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('documentForm.datePublished'), trans('documentForm.datePublished') , false, 'text' , ['datetimepicker']  ) !!}
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-lg-4"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('date_expired',$data,old('date_expired'), trans('documentForm.dateExpired') , trans('documentForm.dateExpired') , false ,'text', ['datetimepicker'] ) !!}
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-lg-4 wiki-parent-checkbox"> 
                <div class="form-group checkbox-form-group">
                    {!! ViewHelper::setCheckbox('pdf_upload',$data,old('pdf_upload'),trans('wiki.menuParent') ) !!}
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-lg-4"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('wiki.parentId'),
                    trans('wiki.headline') ,true  ) !!}
                </div>   
            </div><!--End input box-->
            <div class="clearfix"></div>
            
            <!-- input box-->
            <div class="col-lg-4"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('wiki.subject'),
                    trans('wiki.subject'), true  ) !!}
                </div>   
            </div><!--End input box-->
            
            <div class="clearfix"></div>
            <div class="col-xs-12 editable" data-id='content'>
                
            </div>
            <div class="clearfix"></div>
            
            <div class="col-xs-12 col-md-6">
                <label>{{ trans('wiki.roles') }}*</label>
                <select name="wiki_roles[]" class="form-control select" data-placeholder="{{ trans('wiki.roles') }}">
                    @foreach($wikiStatuses as $status)
                    <option value="{{$status->id}}">
                        {{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="clearfix"></div>
            <br/>