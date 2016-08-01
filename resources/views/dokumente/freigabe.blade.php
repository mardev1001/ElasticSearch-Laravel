{{-- DOKUMENT DETAILS --}}

@extends('master')

@section('page-title') @if( isset($document->documentType->name) ){{ $document->documentType->name }}@endif - Übersicht @stop

@section('content')

<div class="box-wrapper ">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            @if( isset($document->name_long) && $document->name_long != '' )
               <h3 class="title"> 
               @if( $document->document_type_id == 3 )
                  QMR @if( $document->qmr_number != null) {{ $document->qmr_number }}: @endif
               @endif
               {{ $document->name_long }} 
            @else
               <h3 class="title">{{ $document->name }} 
            @endif
               <br><span class="text"><strong>({{ trans('dokumentShow.version') }}: {{ $document->version }}, {{ 
                trans('dokumentShow.status') }}: {{ $document->documentStatus->name }}@if($document->date_published), {{$document->date_published}} @endif)
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
                                                       <a target="_blank" href="{{ url('download/'.str_slug($document->name).'/'.$attachment->file_path) }}" class="link">{{basename($attachment->file_path)}}</a>
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
                            @endif {{-- end if pdf upload --}}
                          
                            <!-- <p>-->
                            <!--   Lorem ipsum qum dare etiamsi del cumsequr. Lorem ipsum qum dare etiamsi del cumsequr. Lorem ipsum qum dare etiamsi  -->
                            <!-- </p> -->
                            <!-- <p>-->
                            <!--    Lorem ipsum qum dare etiamsi  Lorem ipsum qum dare etiamsi del cumsequr. Lorem ipsum qum dare  -->
                            <!--    Lorem ipsum qum dare etiamsi del cumsequr. Lorem ipsu Lorem ipsum qum dare etiamsi del cumsequr.-->
                            <!--</p>-->
                            
                        </div>
                        
                        <div class="clearfix"></div> <br>
                        
                          <div class="footer">

                                @if(count($document->documentUploads))
                                    <div class="attachments">
                                        <span class="text">PDF Vorschau: </span>
                                        <div class="clearfix"></div> <br>

                                        @foreach($document->documentUploads as $k => $attachment)
                                                <!--<a target="_blank" href="#{{$attachment->file_path}}" class="">{{basename($attachment->file_path)}}</a><br>-->
                                        <!--<a target="_blank" href="{{ url('download/'.str_slug($document->name).'/'.$attachment->file_path) }}" class="link">-->
                                        <!--{{-- basename($attachment->file_path) --}} PDF download</a>-->
                                        <!--<br><span class="indent"></span>-->

                                        <object data="{{url('open/'.$document->id.'/'.$attachment->file_path)}}" type="application/pdf" width="100%" height="640">
                                            PDF konnte nicht initialisiert werden. Die Datei können sie <a href="{{url('download/'. $document->id .'/'.$attachment->file_path)}}">hier</a> runterladen.
                                        </object>
                                        <div class="clearfix"></div> <br>
                                        @endforeach
                                    </div>
                                @endif

                                @foreach( $variants as $v => $variant)
                                    @if( ( isset($variant->hasPermission) && $variant->hasPermission == true ))

                                        @if( count( $variant->EditorVariantDocument ) )
                                            <div class="attachments document-attachments">
                                                <span class="text">Dokument Anlage/n: </span> <br>
                                                @foreach($variant->EditorVariantDocument as $k =>$docAttach)
                                                    @if( $docAttach->document_id != $document->id )
                                                        @foreach( $docAttach->document->documentUploads as $key=>$docUpload)
                                                            @if( $key == 0 )
                                                                <a target="_blank" href="{{ url('download/'. $docAttach->document->id .'/'.$docUpload->file_path) }}" class="link">
                                                                    {{$docAttach->document->name_long }}</a> <br> <!-- <span class="indent"></span> -->
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </div><!-- end .attachments .document-attacments -->
                                        @endif
                                    @endif
                                @endforeach

                            </div><!-- end .footer -->
                            
                        
                            
                            
                      
                    </div><!--end col-xs-12-->
                    
                </div>  
            </div>  
   
            <div class="col-sm-4 col-md-3 col-lg-2 btns">
                <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#kommentieren">{{ trans('dokumentShow.commenting') }}</button>
                @if( $authorised == false && $canPublish ==false)
                    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#freigeben">{{ trans('documentForm.freigeben') }}</button>
                    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#noFreigeben">{{ trans('documentForm.noFreigeben') }}</button>
                @elseif( ($authorised == false && $canPublish == true && $published == false ) ||  
                       ($authorised == true && $published == false ) )
                        <a href="/dokumente/{{$document->id}}/publish" class="btn btn-primary pull-right">{{ trans('documentForm.publish') }}</a>
                @endif
                
            </div>
        </div><!--end row-->
        </div><!-- end .box -->
        </div>
        <div class="clearfix"></div> <br/>
  
  <div class="box-wrapper ">             
    <!-- user comments -->
    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-12 box-wrapper home">
                <h1 class="title">{{ trans('wiki.commentUser') }}</h1>
                <div class="box home">
                    <div class="commentsMy">
                        @if(count($documentCommentsUser))
                            @foreach($documentCommentsUser as $k => $comment)
                                <div class="comment-{{++$k}}">
                                    <span class="comment-header">
                                        <a href="{{url('/dokumente/'. $comment->document->published->url_unique)}}">
                                            <strong>{{ $comment->document->name }}</strong>
                                        </a>
                                        , {{ $comment->created_at }}
                                    </span> <br>
                                    <span class="comment-body">
                                        {{ str_limit($comment->comment, $limit = 200, $end = ' ...') }}
                                    </span>
                                </div>
                                <div class="clearfix"></div><br>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end user comments -->
        <div class="clearfix"></div> 
  </div>
  <div class="clearfix"></div><br/>
  
    <!-- freigaber comments -->
    @if(count($documentCommentsFreigabe) )
    <div class="box-wrapper "> 
        <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-12 box-wrapper home">
                <h1 class="title">{{ trans('wiki.commentAdmin') }}</h1>
                <div class="box home">
                    <div class="commentsMy">
                        
                            @foreach($documentCommentsFreigabe as $k => $comment)
                                <div class="comment-{{++$k}}">
                                    <span class="comment-header">
                                        <a href="{{url('/dokumente/'. $comment->document->published->url_unique)}}">
                                            <strong>{{ $comment->document->name }}</strong>
                                        </a>
                                        , {{ $comment->created_at }}
                                    </span> <br>
                                    <span class="comment-body">
                                        {{ str_limit($comment->comment, $limit = 200, $end = ' ...') }}
                                    </span>
                                </div>
                                <div class="clearfix"></div><br>
                            @endforeach
                        
                    </div>
                </div>
            </div>
        </div>
    </div><!-- end freigaber comments -->
    </div>
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
