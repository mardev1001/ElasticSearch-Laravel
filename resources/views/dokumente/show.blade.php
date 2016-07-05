{{-- DOKUMENT DETAILS --}}

@extends('master')

@section('page-title') @if( isset($document->documentType->name) ){{ $document->documentType->name }}@endif - Übersicht @stop

@section('content')

<div class="box-wrapper ">
    <div class="row">
        <div class="col-md-12 col-lg-12">
            @if( isset($document->name_long) && $document->name_long != '' )
               <h3 class="title">{{ $document->name_long }}
            @else
               <h3 class="title">{{ $document->name }}
            @endif
               <span class="text"><b>({{ trans('dokumentShow.version') }}: {{ $document->version }}, {{
                trans('dokumentShow.status') }}: {{ $document->documentStatus->name }})
                </b>
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
                
                        <div class="header">
                            <p class="text-small">{{ $document->created_at }}</p> <!-- date placeholder -->
                            @if($document->documentAdressats)
                            <p><b>{{ trans('dokumentShow.adressat') }}:</b> {{ $document->documentAdressats->name }}</p> <!-- Adressat optional -->
                            @endif
                             @if( !empty( $document->betreff ))
                                <p><b>{{ trans('dokumentShow.subject') }}:</b> {{ $document->betreff }}</p> <!-- Subject -->
                             @endif
                        </div>
                  
                         <br>
                        <div class="content">
                            <!--<p class="text-strong title-small">{{ trans('dokumentShow.content') }}</p>-->
                            
                            @if(!$document->pdf_upload)
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
                                               <div>
                                                   {!! ($variant->inhalt) !!}
                                               </div>
                                            </div>
                                       @endforeach
                                   @endif
                                </div>
                               
                            @endif
                          
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
                                <span class="text">Dokument Anhäng/e: </span>
                                @foreach($document->documentUploads as $k =>$attachment)
                                    <!--<a target="_blank" href="#{{$attachment->file_path}}" class="">{{basename($attachment->file_path)}}</a><br>-->
                                   <a target="_blank" href="{{ url('download/'.str_slug($document->name).'/'.$attachment->file_path) }}" class="link">
                                {{-- basename($attachment->file_path) --}} PDF download</a>
                                   <br><span class="indent"></span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                      @if( count( $documentComments ) )
                            <h3> {{ trans('dokumentShow.userCommentTitle') }} </h3>
                             @foreach( $documentComments as $comment ) 
                                 <b>{{ $comment->user->title }} {{ $comment->user->last_name }} -
                                  {{ $comment->betreff }} - {{$comment->created_at}}</b><br/>
                                 <p>{!! $comment->comment !!}</p>
                             @endforeach
                        
                        @endif
                    </div><!--end col-xs-12-->
                </div><!--end row-->
                <div class="clearfix"></div> 
               
            </div>

            <div class="col-sm-4 col-md-3 col-lg-2 btns">
                <a href="{{route('dokumente.edit', $document->id)}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.edit')}} </a>
                <button class="btn btn-primary pull-right">{{ trans('dokumentShow.deactivate') }}</button>
                <!--<a href="#" class="btn btn-primary pull-right">{{ trans('dokumentShow.new-version') }}</a>-->
                <a href="/dokumente/new-version/{{$document->id}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.new-version') }}</a>
                <a href="/dokumente/historie/{{$document->id}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.history') }}</a>
                <button class="btn btn-primary pull-right">{{ trans('dokumentShow.favorite') }}</button>
                <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#kommentieren">{{ trans('dokumentShow.commenting') }}</button>
                <a href="/dokumente/{{$document->id}}/freigabe" class="btn btn-primary pull-right">{{ trans('dokumentShow.approve') }}</a>
                <button class="btn btn-primary pull-right">{{ trans('dokumentShow.disapprove') }}</button>
                <a href="#" class="btn btn-primary pull-right">PDF ansehen</a>
                <!--<button class="btn btn-primary pull-right">{{ trans('dokumentShow.download') }}</button>-->
                
                <!--<a href="{{route('dokumente.edit', $document->id)}}" class="btn btn-primary pull-right"><i class="fa fa-edit"></i> {{ trans('dokumentShow.edit')}} </a>-->
                <!--<button class="btn btn-primary pull-right"><i class="fa fa-power-off"></i> {{ trans('dokumentShow.deactivate') }}</button>-->
                <!--<button class="btn btn-primary pull-right"><i class="fa fa-files-o"></i> {{ trans('dokumentShow.new-version') }}</button>-->
                <!--<button class="btn btn-primary pull-right"><i class="fa fa-history"></i> {{ trans('dokumentShow.history') }}</button>-->
                <!--<button class="btn btn-primary pull-right"><i class="fa fa-star-o"></i> {{ trans('dokumentShow.favorite') }}</button>-->
                <!--<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#kommentieren"><i class="fa fa-comment-o"></i> {{ trans('dokumentShow.commenting') }}</button>-->
                <!--<button class="btn btn-primary pull-right"><i class="fa fa-check"></i> {{ trans('dokumentShow.approve') }}</button>-->
                <!--<button class="btn btn-primary pull-right"><i class="fa fa-times"></i> {{ trans('dokumentShow.disapprove') }}</button>-->
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
