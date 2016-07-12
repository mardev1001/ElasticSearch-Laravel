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
                                
                               @foreach( $variants as $v => $variant)
                                   @if( isset($variant->hasPermission) && $variant->hasPermission == true )
                                      <div>
                                          {!! ($variant->inhalt) !!}
                                      </div>
                                   @endif
                               @endforeach
                               
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
                @if( $document->document_status_id  != 3)
                    <a href="{{route('dokumente.edit', $document->id)}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.edit')}} </a>
                @endif
                <button class="btn btn-primary pull-right">{{ trans('dokumentShow.deactivate') }}</button>
                <!--<a href="#" class="btn btn-primary pull-right">{{ trans('dokumentShow.new-version') }}</a>-->
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
                
                
                <a href="/dokumente/{{$document->id}}/pdf" class="btn btn-primary pull-right">PDF ansehen</a>
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
