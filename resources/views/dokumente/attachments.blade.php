@extends('master')
    @section('content')
     
        <div class="row">
            <div class="col-xs-12 col-md-12 ">
                <div class="fixed-row">
                    <div class="fixed-position ">
                        <h1 class="page-title">
                            {{ trans('controller.attachment') }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-xs-12">
                <ul class="nav nav-tabs" id="tabs">
                   @if( count($data->editorVariant) > 0) 
                       @foreach( $data->editorVariant as $variant)
                           <li><a href="#variant{{$variant->variant_number}}" data-toggle="tab">Anhänge zu Variante {{$variant->variant_number}}</a></li>
                       @endforeach
                   @endif
                </ul>
                
                <div class="tab-content">
                    @if( count($data->editorVariant) > 0) 
                        @foreach( $data->editorVariant as $variant)
                            <div class="tab-pane" id="variant{{$variant->variant_number}}">
                                <div class="col-xs-12 box-wrapper home">
                                    <h2 class="title">Anhänge:</h2>
                                    <div class="box home">
                                        <div class="tree-view" data-selector="a">
                                            <div class="rundschreibenNeu hide">
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end box-wrapper --> 
                                content
                            </div><!--end tab pane -->
                        @endforeach
                    @endif
                </div>
            </div>
        </div>             
        
        <div class="clearfix"></div>  
        
        <!--Select existing document-->         
        <div class="row">
            {!! Form::open([
            'url' => 'dokumente/rechte-und-freigabe/'.$data->id,
            'method' => 'POST',
            'class' => 'horizontal-form' ]) !!}
            <div class="col-xs-12">
                <h3>{{trans('documentForm.existingDocument')}}</h3>
            </div>
                <!--option box-->
                <div class="col-xs-8 col-md-6">
                    <div class="form-group">
                        {!! ViewHelper::setSelect($documents,'document_id',$data,old('document_id'),
                                trans('documentForm.documents'), trans('documentForm.documents'),true ) !!}
                    </div>   
                </div>
                <!--end option box-->
                
                <!--button box-->
                <div class="col-xs-8 col-md-6">
                    <div class="form-group">
                        <br/>
                        <br/>
                      <button class="btn btn-primary" type="submit">Hinzufügen</button>
                    </div>   
                </div>
                <!--end button box-->
                
            </form>
        </div><!--end Select existing document-->
        
        
        <!--create new document-->
        <div class="row">
            {!! Form::open([
            'url' => 'dokumente',
            'method' => 'POST',
            'class' => 'horizontal-form' ]) !!}
            <div class="col-xs-12">
                <h3>{{trans('documentForm.newDocument')}}</h3>
            </div>
                
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />

                <!-- input box-->
                <div class="col-lg-3"> 
                    <div class="form-group">
                       
                    <label class="control-label">{{ ucfirst(trans('documentForm.documentType')) }}  {!! ViewHelper::asterisk() !!}</label>
                    <select name="document_type_id" class="form-control select" 
                    data-placeholder="{{ ucfirst(trans('documentForm.documentType')) }}" required disabled>
                    <option></option>
                    @if( count($documentTypes) >0 ){
                       @foreach($documentTypes as $collection){
                           <option value="{{$collection->id}}" 
                                @if($collection->id == 5 )
                                    selected
                                @endif >
                               {{$collection->name}}
                           </option>
                        @endforeach
                    @endif
                </select>
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
                        {!! ViewHelper::setUserSelect($mandantUsers,'owner_user_id',$data,old('owner_user_id'),
                                trans('documentForm.owner'), trans('documentForm.owner') ) !!}
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
                        {!! ViewHelper::setInput('search_tags',$data,old('search_tags'),trans('documentForm.searchTags') , 
                               trans('documentForm.searchTags') , true  ) !!} <!-- add later data-role="tagsinput"-->
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
                <div class="col-lg-3"> 
                    <div class="form-group">
                        <br>
                        {!! ViewHelper::setCheckbox('pdf_upload',$data,old('pdf_upload'),trans('documentForm.pdfUpload') ) !!}
                    </div>   
                </div><!--End input box-->
                
                <!-- input box-->
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! ViewHelper::setArea('summary',$data,old('summary'),trans('documentForm.summary') ) !!}
                    </div>   
                </div><!--End input box-->  
                
                 <!-- input box-->
                    <div class="col-lg-6"> 
                        <div class="form-group">
                            <label>Dokumentenupload</label>
                            <input type="file" name="file[]" class="form-control" multiple 
                            @if( $data->documentUploads()->count() < 1 )
                                required
                            @endif />
                        </div>   
                    </div><!--End input box-->
                <div class="clearfix"></div>
                <div class="col-xs-12 form-buttons">
                    <button class="btn btn-white" type="reset">{{ trans('formWrapper.reset') }}</button>
                    <button class="btn btn-primary" type="submit">{{ trans('formWrapper.save') }}</button>
                </div>
                
                <div class="clearfix"></div> <br/>

            </form>
        </div><!--end create new document-->
        
    @stop
    
    
    
  @if( count($data->editorVariant) ) 
      @section('script')
        <script type="text/javascript">
            $(document).ready(function(){
               	if( $('.nav-tabs li.active').length < 1 ){
              	    $('.nav-tabs li').first().addClass('active'); 
              	    $('.tab-content .tab-pane').first().addClass('active'); 
      	        }
            });//end document ready
        </script>
      @stop
  @endif