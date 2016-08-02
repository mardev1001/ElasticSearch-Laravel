{{-- DOKUMENT DETAILS --}}

@extends('master')

@section('page-title') @if( isset($document->documentType->name) ){{ $document->documentType->name }}@endif - Übersicht @stop

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
                            <strong>({{ trans('dokumentShow.version') }}: {{ $document->version }}, {{ trans('dokumentShow.status') }}: {{ $document->documentStatus->name }}
                                @if($document->date_published), {{$document->date_published}}@endif)
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
                            <!--    <p class="text-small">{{ $document->created_at }}</p> -->
                            <!--    @if($document->documentAdressats)-->
                            <!--    <p><b>{{ trans('dokumentShow.adressat') }}:</b> {{ $document->documentAdressats->name }}</p> -->
                            <!--    @endif-->
                        <!--     @if( !empty( $document->betreff ))-->
                            <!--        <p><b>{{ trans('dokumentShow.subject') }}:</b> {{ $document->betreff }}</p> -->
                            <!--     @endif-->
                        <!--</div>-->

                            <div class="content">
                                <!--<p class="text-strong title-small">{{ trans('dokumentShow.content') }}</p>-->

                                @if(!$document->pdf_upload)

                                    @foreach( $variants as $v => $variant)
                                        @if( isset($variant->hasPermission) && $variant->hasPermission == true )
                                            <div>
                                                {{--<pre> {!! ($variant->inhalt) !!} </pre>--}}
                                                {!! ViewHelper::stripTags($variant->inhalt, array('div' ) ) !!}
                                            </div>
                                        @endif
                                    @endforeach

                                @endif

                            </div><!-- end .content -->

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
                                                <div class="flexbox-container">
                                                @foreach($variant->EditorVariantDocument as $k =>$docAttach)
                                                    @if( $docAttach->document_id != $document->id )
                                                        @foreach( $docAttach->document->documentUploads as $key=>$docUpload)
                                                            @if( $key == 0 )
                                                             <!--<a href="{{route('dokumente.edit', $docAttach->document->id)}}" class="btn btn-primary">-->
                                                             <a href="{{route('dokumente.edit', $docAttach->document->id)}}" ><span class="icon icon-edit inline-block"></span></a> 
                                                             <a target="_blank" href="{{ url('download/'. $docAttach->document->id .'/'.$docUpload->file_path) }}" class="link pl20">
                                                               {!! ViewHelper::stripTags($docAttach->document->name_long, array('p' ) ) !!}</a> <br> <!-- <span class="indent"></span> -->
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                                </div>
                                            </div><!-- end .attachments .document-attacments -->
                                        @endif
                                    @endif
                                @endforeach

                            </div><!-- end .footer -->

                          

                        </div><!--end col-xs-12-->
                    </div><!--end row-->

                    <div class="clearfix"></div> <br>
                

                </div><!-- end .col-sm-8 .col-md-9 .col-lg-10 -->
                

                <div class="col-sm-4 col-md-3 col-lg-2 btns">
                    @if( $document->document_status_id  != 3)
                        <a href="{{route('dokumente.edit', $document->id)}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.edit')}} </a>
                    @else
                        <a href="{{route('dokumente.edit', $document->id)}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.edit')}} </a>
                    @endif

                    <a href="/dokumente/{{$document->id}}/activate" class="btn btn-primary pull-right">
                        @if( $document->active  == false)
                            {{ trans('dokumentShow.activate') }}
                        @else
                            {{ trans('dokumentShow.deactivate') }}
                        @endif</a>
                    {{-- <a href="#" class="btn btn-primary pull-right">{{ trans('dokumentShow.new-version') }}</a> --}}
                    <a href="/dokumente/new-version/{{$document->id}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.new-version') }}</a>
                    <a href="/dokumente/historie/{{$document->id}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.history') }}</a>

                    <a href="/dokumente/{{$document->id}}/favorit" class="btn btn-primary pull-right">
                        @if( $document->hasFavorite == false)
                            {{ trans('dokumentShow.favorite') }}
                        @else
                            {{ trans('dokumentShow.unFavorite') }}
                        @endif</a>

                    <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#kommentieren">{{ trans('dokumentShow.commenting') }}</button>



                    @if(count(Request::segments() ) == 2 && is_numeric(Request::segment(2) ) )
                        @if( $authorised == false && $canPublish ==false && $published == false)
                            <a href="/dokumente/{{$document->id}}/freigabe" class="btn btn-primary pull-right">{{ trans('dokumentShow.approve') }}</a>
                        @elseif( ($authorised == false && $canPublish == true && $published == false ) ||
                           ($authorised == true && $published == false ) )
                            <a href="/dokumente/{{$document->id}}/publish" class="btn btn-primary pull-right">{{ trans('documentForm.publish') }}</a>
                        @endif
                    @endif

                    @if(count($document->documentUploads))
                        {{-- The PDF download button is only shown if the document has PDF Rundschreiben / PDF uploads --}}
                        @foreach($document->documentUploads as $k => $attachment)
                            @if($k > 0) @break @endif
                            <a href="{{url('download/'. $document->id .'/'. $attachment->file_path)}}" class="btn btn-primary pull-right">Download</a>
                        @endforeach
                    @else
                        {{-- The link for generating PDF from the document content should be here (the content you see on the overview) --}}
                        <a target="_blank" href="/dokumente/{{$document->id}}/pdf" class="btn btn-primary pull-right">Druckvorschau</a>
                    @endif

                </div>

                <div class="clearfix"></div>
                <!-- modal start -->
                <div id="kommentieren" class="modal fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title">{{ trans('dokumentShow.commenting') }}</h4>
                            </div>

                            {!! Form::open([
                               'url' => '/comment/'.$document->id,
                               'method' => 'POST',
                               'class' => 'horizontal-form']) !!}
                            <input type="hidden" name="page" value="/dokumente/{{$document->id}}" />
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
            </div>
        </div>
    </div>
     
    <div class="clearfix"></div><br>
    
    <!-- user comments -->
    <div class="row">
        <div class="col-xs-12">
            <div class="col-xs-12 box-wrapper home">
                <h1 class="title">{{ trans('wiki.commentUser') }}</h1>
                <div class="box home">
                    <div class="commentsMy">
                        @if(count($documentComments))
                            @foreach($documentComments as $k => $comment)
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
    
    
    <!-- freigaber comments -->
    @if(count($documentCommentsFreigabe) )
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
    @endif
    
       
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
