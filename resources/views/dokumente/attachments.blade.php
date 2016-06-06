@extends('master')
    @section('content')
     
        <div class="row">
            <div class="col-xs-12 col-md-12 white-bgrnd">
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
        <div class="box-wrapper col-md-12">
            <div class="box">
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
                                        <div class="row">
                                            <div class="col-xs-6 ">
                                                <h2 class="title">Anhänge für variant {{$variant->variant_number}}:</h2>
                                                <div class="">
                                                    @if( array_key_exists( $variant->id,$attachmentArray ) && $attachmentArray[$variant->id] != '[]'  )
                                                          
                                                        <div class="tree-view" data-selector="variant-tree-{{$variant->variant_number}}">
                                                            <div class="variant-tree-{{$variant->variant_number}} hide">
                                                                {{ ($attachmentArray[$variant->id]) }}
                                                            </div>
                                                        </div>
                                                      
                                                    @else
                                                        <p class="text-danger">No attachments for variant {{$variant->variant_number}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                
                                    <div class="clearfix"></div>  
                                    
                                    <!--Select existing document-->         
                                    <div class="row">
                                        {!! Form::open([
                                        'url' => 'dokumente/anhange/'.$data->id,
                                        'method' => 'POST',
                                        'class' => 'horizontal-form' ]) !!}
                                        <div class="col-xs-12">
                                            <h3>{{trans('documentForm.existingDocument')}}</h3>
                                        </div>
                                        
                                            <input type="hidden" name="variant_id" value="{{ $variant->id }}" />
                                            
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
                                                  <button class="btn btn-primary" type="submit" name="attach" value="attach">Hinzufügen</button>
                                                </div>   
                                            </div>
                                            <!--end button box-->
                                            
                                        </form>
                                    </div><!--end Select existing document-->
                                    
                                    
                                    <!--create new document-->
                                    <div class="row">
                                        {!! Form::open([
                                        'url' => 'dokumente/anhange/'.$data->id,
                                        'method' => 'POST',
                                        'enctype' => 'multipart/form-data',
                                        'class' => 'horizontal-form form-check' ]) !!}
                                        <div class="col-xs-12">
                                            <h3>{{trans('documentForm.newDocument')}}</h3>
                                        </div>
                                            
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />
                                            <input type="hidden" name="document_id" value="{{ $data->id }}" />
                                            <input type="hidden" name="variant_id" value="{{ $variant->id }}" />
                            
                                            <!-- input box-->
                                            <div class="col-lg-3"> 
                                                <div class="form-group">
                                                <select name="document_type_id" class="form-control select" 
                                                data-placeholder="{{ ucfirst(trans('documentForm.documentType')) }} *" required disabled>
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
                                                    <select name="document_coauthor[]" class="form-control select" data-placeholder="{{ trans('documentForm.coauthor') }}" multiple>
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
                                                @if( isset($backButton) )
                                                    <a href="{{$backButton}}" class="btn btn-info"><span class="fa fa-chevron-left"></span> Zurück</a>
                                                @endif
                                                <button class="btn btn-primary" type="submit" name="save" value="save">
                                                    <span class="fa fa-floppy-o"></span> Speichern
                                                </button>
                                                <button class="btn btn-primary" type="button" name="next" value="next"
                                                @if( isset($nextButton) ) data-link="{{$nextButton}}" @endif  > 
                                                    <span class="fa fa-chevron-right"></span>
                                                    Weiter
                                                </button>
                                            </div>
                                            
                                            <div class="clearfix"></div> <br/>
                            
                                        </form>
                                    </div><!--end create new document-->
                  </div><!--end tab pane -->
                                @endforeach
                            @endif
                        </div>
                    </div>
                 </div>
             </div>      
         </div>           
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