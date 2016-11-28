{{-- DOKUMENT DETAILS --}}

@extends('master')

@section('page-title') {{ ucfirst( trans('controller.dokumente')) }} - @if( isset($document->documentType->name) ){{ $document->documentType->name }}@endif @stop

@section('content')

<div class="box-wrapper ">
    <div class="row">
        <div class="col-md-12 col-lg-12">
           <h3 class="title">
            @if( isset($document->name_long) && $document->name_long != '' )
                @if( $document->document_type_id == 3 )
                        QMR @if( $document->qmr_number != null) {{ $document->qmr_number }}@if( $document->additional_letter ){{ $document->additional_letter }}@endif: @endif
                @endif
                {!! $document->name_long !!}
            @else
                {!! $document->name !!}
            @endif  
                <br>
                <span class="text">
                    <strong>({{ trans('dokumentShow.version') }}: {{ $document->version }}, {{ trans('dokumentShow.status') }}: {{ $document->documentStatus->name }}@if($document->date_published), {{$document->date_published}}, @endif
                    @if(isset($document->owner) ){{ $document->owner->first_name.' '.$document->owner->last_name }}@endif)
                    </strong>
                </span>
            </h3>
        </div>
    </div>


    <div class="box">
        <div class="row">
            <div class="col-sm-8 col-md-9 col-lg-10">
                
                <div class="clearfix"></div> 
                
                <div class="row">
                    <div class="col-xs-12">
                
                        <!--<div class="header">-->
                        <!--    <p class="text-small">{{ $document->created_at }}</p> <!-- date placeholder -->
                        <!--    @if($document->documentAdressats)-->
                        <!--    <p><b>{{ trans('dokumentShow.adressat') }}:</b> {{ $document->documentAdressats->name }}</p> <!-- Adressat optional -->
                        <!--    @endif-->
                        <!--     @if( !empty( $document->betreff ))-->
                        <!--        <p><b>{{ trans('dokumentShow.subject') }}:</b> {{ $document->betreff }}</p> <!-- Subject -->
                        <!--     @endif-->
                        <!--</div>-->
                  
                         <br>
                        <div class="content">
                            <p class="text-strong title-small">{{ trans('dokumentShow.content') }}</p>
                            
                            @if(!$document->pdf_upload )
                            <div class="row">
                                <div class="parent-tabs col-xs-12 col-md-12">
                                    <ul class="nav nav-tabs" id="tabs">
                                       @if( count($document->editorVariantOrderBy) ) 
                                           @foreach( $document->editorVariantOrderBy as $k=>$variant)
                                               <li @if($k == 0) class="active" @endif><a href="#variant{{$variant->variant_number}}" data-toggle="tab">Variante {{$variant->variant_number}}</a></li>
                                           @endforeach
                                       @endif
                                    </ul>
                                    
                                    <div class="tab-content">
                                       @if( count($document->editorVariant) ) 
                                           @foreach( $document->editorVariant as $v => $variant)
                                               <div class="tab-pane @if($v == 0) active @endif" id="variant{{$variant->variant_number}}">
                                                
                                                   @if(count($document->documentUploads))
                                                    <div class="attachments">
                                                        <span class="text">Dokument Anlage/n: </span>
                                                        @foreach($document->documentUploads as $attachment)
                                                            <!--<a target="_blank" href="#{{$attachment->file_path}}" class="">{{basename($attachment->file_path)}}</a><br>-->
                                                           <a target="_blank" href="{{ url('download/'.$document->id.'/'.$attachment->file_path) }}" class="link">{{basename($attachment->file_path)}}</a>
                                                           <br><span class="indent"></span>
                                                        @endforeach
                                                    </div>
                                                    @endif
                                                    <div>
                                                       {!! ViewHelper::stripTags($variant->inhalt, array('div' ) ) !!}
                                                    </div>
                                                </div>
                                           @endforeach
                                       @endif
                                    </div>
                                </div><!-- end .parent-tabs -->
                            </div><!-- end .row -->
                            @endif {{-- end if pdf upload --}}
                        </div>
                        
                        <div class="clearfix"></div> <br>
                        
                          <div class="footer">

                                @if(count($document->documentUploads))
                                    @if( ViewHelper::hasPdf( $document ) == true)
                                    <div class="attachments">
                                        <span class="text">PDF Vorschau: </span>
                                        <div class="clearfix"></div> <br>

                                        @foreach($document->documentUploads as $k => $attachment)
                                                <!--<a target="_blank" href="#{{$attachment->file_path}}" class="">{{basename($attachment->file_path)}}</a><br>-->
                                        <!--<a target="_blank" href="{{ url('download/'.str_slug($document->name).'/'.$attachment->file_path) }}" class="link">-->
                                        <!--{{-- basename($attachment->file_path) --}} PDF download</a>-->
                                        <!--<br><span class="indent"></span>-->
                                         @if( ViewHelper::htmlObjectType( $document,$attachment ) == 'application/pdf' )
                                            <object data="{{url('open/'.$document->id.'/'.$attachment->file_path)}}" 
                                            type="{{ ViewHelper::htmlObjectType( $document,$attachment ) }}" width="100%"  @if(ViewHelper::htmlObjectType( $document,$attachment )=='application/pdf') height="640" @endif>
                                                PDF konnte nicht initialisiert werden. Die Datei können sie <a href="{{url('download/'. $document->id .'/'.$attachment->file_path)}}">hier</a> runterladen.
                                            </object>
                                        @endif
                                        <div class="clearfix"></div> <br>
                                        @endforeach
                                    </div>
                                    @endif
                                @endif

                                @foreach( $variants as $v => $variant)
                               
                                    
                                        @if( count( $variant->EditorVariantDocument ) )
                                            <div class="attachments document-attachments">
                                                <span class="text">Dokument Anlage/n für Variante {{$variant->variant_number}}: </span> <br>
                                                <div class="">
                                                @foreach($variant->EditorVariantDocument as $k =>$docAttach)
                                                    @if( $docAttach->document_id != $document->id )
                                                        @foreach( $docAttach->document->documentUploads as $key=>$docUpload)
                                                            @if( $key == 0 )
                                                            
                                                             <!--<a href="{{route('dokumente.edit', $docAttach->document->id)}}" class="btn btn-primary">-->
                                                             <div class="row flexbox-container">
                                                                 <div class="col-md-12">
                                                                 <a href="{{route('dokumente.edit', $docAttach->document->id)}}" class="no-underline">
                                                                     <span class="icon icon-edit inline-block"></span>
                                                                 </a> 
                                                                 <a target="_blank" href="{{ url('download/'. $docAttach->document->id .'/'.$docUpload->file_path) }}" class="link pl10 pr10">
                                                                   {!! ViewHelper::stripTags($docAttach->document->name, array('p' ) ) !!}</a> <br> <!-- <span class="indent"></span> -->
                                                                </div>
                                                            </div>
                                                            <div class="clearfix"></div>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                                </div>
                                            </div><!-- end .attachments .document-attacments -->
                                        @endif
                                @endforeach

                            </div><!-- end .footer -->
                            
                        
                            
                            
                      
                    </div><!--end col-xs-12-->
                    
                </div>  
            </div>  
   
            <div class="col-sm-4 col-md-3 col-lg-2 btns scrollable-document">
                <!--<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#kommentieren">{{ trans('dokumentShow.commenting') }}</button>-->
                @if( ViewHelper::universalDocumentPermission( $document, false, false, true ) ) 
                    <a href="{{route('dokumente.edit', $document->id)}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.edit')}} </a> 
                @endif
                    
                    
                @if( $authorised == false && $canPublish == false )
                 
                    @if( count( $document->documentApprovals->where('user_id',Auth::user()->id )->where('date_approved',null) ) == 1 )
                        @if( $document->documentType->document_art == 1)
                            @if( ViewHelper::universalHasPermission( array(13) ) == true )
                                 <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#freigeben">{{ trans('documentForm.freigeben') }}</button>
                                 <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#noFreigeben">{{ trans('documentForm.noFreigeben') }}</button>
                            @endif
                        @else
                            @if( (ViewHelper::universalHasPermission( array(10) ) == true && ViewHelper::isThisDocumentFreigeber($document) == true )
                            || ViewHelper::universalHasPermission( array() ) == true )
                                 <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#freigeben">{{ trans('documentForm.freigeben') }}</button>
                                 <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#noFreigeben">{{ trans('documentForm.noFreigeben') }}</button>
                            @endif
                        @endif
                    @endif
                   
                @elseif( ($authorised == false && $canPublish == true && $published == false ) ||  
                       ($authorised == true && $published == false )  )
                      
                        @if( ( ( $document->documentType->document_art == 1 &&
                                ViewHelper::universalHasPermission( array(13) ) == true ) ||
                                ( $document->documentType->document_art == 0 &&
                                ViewHelper::universalHasPermission( array(11) ) == true ) )
                                && ViewHelper::universalDocumentPermission( $document, false, false, true ) )
                            <a href="/dokumente/{{$document->id}}/publish" class="btn btn-primary pull-right">{{ trans('documentForm.publish') }}</a>
                        @endif
                @endif
                
                @if(count($document->documentUploads) && ($document->pdf_upload == 1 ) )
                    {{-- The PDF download button is only shown if the document has PDF Rundschreiben / PDF uploads --}}
                    @foreach($document->documentUploads as $k => $attachment)
                        @if($k > 0) @break @endif
                        <a href="{{url('download/'. $document->id .'/'. $attachment->file_path)}}" class="btn btn-primary pull-right">Download / Druck</a>
                    @endforeach
                @else
                    {{-- The link for generating PDF from the document content should be here (the content you see on the overview) --}}
                    <a target="_blank" href="/dokumente/{{$document->id}}/pdf" class="btn btn-primary pull-right">Druckvorschau</a>
                @endif
                
            </div>
        </div><!--end row-->
        </div><!-- end .box -->
        </div>
        <div class="clearfix"></div> <br/>
  
  
  
  
       @if(count($documentCommentsUser))
          {!! ViewHelper::generateCommentBoxes($documentCommentsUser, trans('wiki.commentUser'), true ) !!}
       @endif
  
  <div class="clearfix"></div><br/>
  
    <!-- freigaber comments -->
    @if(count($documentCommentsFreigabe) )
        {!! ViewHelper::generateCommentBoxes($documentCommentsFreigabe, trans('wiki.commentAdmin'), true ) !!}
    @endif
          

            
            
            <div class="clearfix"></div> 
         <!-- modal start -->   
            <div id="kommentieren" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hiddetn="true">&times;</span>
                            </button>
                            <h4 class="modal-title">{{ trans('dokumentShow.commenting') }}</h4>
                        </div>
            
                        {!! Form::open([
                           'url' => '/comment/'.$document->id,
                           'method' => 'POST',
                           'class' => 'horizontal-form']) !!}
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('dokumentShow.subject') }}</label>
                                    <input type="text" name="betreff" class="form-control" placeholder="{{ trans('dokumentShow.subject') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{ trans('dokumentShow.comment') }}</label>
                                    <textarea name="comment" cols="30" rows="5" class="form-control" placeholder="{{ trans('dokumentShow.comment') }}"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('dokumentShow.close') }}</button>
                                <button type="submit" class="btn btn-primary">{{ trans('dokumentShow.save') }}</button>
                            </div>
                        </form>
            
                    </div>
                </div>
            </div>  <!-- modal end -->  
            
            <!-- modal start -->   
            <div id="freigeben" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hiddetn="true">&times;</span>
                            </button>
                            <h4 class="modal-title">{{ trans('documentForm.freigeben') }}</h4>
                        </div>
            
                        {!! Form::open([
                           'url' => '/dokumente/authorize/'.$document->id,
                           'method' => 'POST',
                           'class' => 'horizontal-form']) !!}
                           <input type="hidden" value="1" name="validation_status" />
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('dokumentShow.subject') }}</label>
                                    <input type="text" name="betreff" class="form-control" placeholder="{{ trans('dokumentShow.subject') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{ trans('dokumentShow.comment') }}</label>
                                    <textarea name="comment" cols="30" rows="5" class="form-control" placeholder="{{ trans('dokumentShow.comment') }}"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('dokumentShow.close') }}</button>
                                <button type="submit" class="btn btn-primary">{{ trans('dokumentShow.save') }}</button>
                            </div>
                        </form>
            
                    </div>
                </div>
            </div>  <!-- modal end -->  
            
            <!-- modal start -->   
            <div id="noFreigeben" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hiddetn="true">&times;</span>
                            </button>
                            <h4 class="modal-title">{{ trans('documentForm.noFreigeben') }}</h4>
                        </div>
            
                        {!! Form::open([
                           'url' => '/dokumente/authorize/'.$document->id,
                           'method' => 'POST',
                           'class' => 'horizontal-form']) !!}
                           <input type="hidden" value="2" name="validation_status" />
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="form-label">{{ trans('dokumentShow.subject') }}</label>
                                    <input type="text" name="betreff" class="form-control" placeholder="{{ trans('dokumentShow.subject') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">{{ trans('dokumentShow.comment') }}</label>
                                    <textarea name="comment" cols="30" rows="5" class="form-control" placeholder="{{ trans('dokumentShow.comment') }}"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('dokumentShow.close') }}</button>
                                <button type="submit" class="btn btn-primary">{{ trans('dokumentShow.save') }}</button>
                            </div>
                        </form>
            
                    </div>
                </div>
            </div>  <!-- modal end -->  
        
@stop        
        @if( isset( $document->document_type_id ) )
           @section('preScript')
               <!-- variable for expanding document sidebar-->
               <script type="text/javascript">
                    var documentType = "{{ $document->documentType->name}}";
                   
                      
               </script>
               
               <!--patch for checking iso category document-->
                @if( isset($document->isoCategories->name) )
                    <script type="text/javascript">   
                        if( documentType == 'ISO Dokument')
                            var isoCategoryName = '{{ $document->isoCategories->name}}';
                    </script>
                @endif
               <!-- End variable for expanding document sidebar-->
           @stop
       @endif
