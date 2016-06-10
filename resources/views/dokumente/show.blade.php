{{-- DOKUMENT DETAILS --}}

@extends('master')

@section('page-title') {{ $document->documentType->name }} @if($document->pdf_upload) PDF @endif - Übersicht @stop

@section('content')

<div class="box-wrapper">
    <div class="row">
        <div class="col-lg-12">
           <h3 class="title">{{ $document->name }}
               <span class="text"><b>({{ trans('dokumentShow.version') }}: {{ $document->version }}, {{
                trans('dokumentShow.status') }}: {{ $document->documentStatus->name }})
                </b>
              </span>
          </h3> 
        </div>
    </div>
    <div class="box">
        <div class="row">
            <div class="col-lg-10">
                
                <div class="clearfix"></div> 
                
                <div class="row">
                    <div class="col-xs-12">
                
                        <div class="header">
                            <p class="text-small">11.05.16</p> <!-- date placeholder -->
                            @if($document->documentAdressats)
                            <p><b>{{ trans('dokumentShow.adressat') }}:</b> {{ $document->documentAdressats->name }}</p> <!-- Adressat optional -->
                            @endif
                             @if( !empty( $document->betreff ))
                                <p><b>{{ trans('dokumentShow.subject') }}:</b> {{ $document->betreff }}</p> <!-- Subject -->
                             @endif
                        </div>
                  
                         <br>
                        <div class="content">
                            <p class="text-strong title-small">{{ trans('dokumentShow.content') }}</p>
                            
                            @if(!$document->pdf_upload)
                                @foreach($document->editorVariant as $variant)
                                <div class="variant-{{$variant->variant_number}}">
                                    <p class="title-small">{{ trans('dokumentShow.variant') }} {{$variant->variant_number}}</p>
                                    <p>{{ strip_tags($variant->inhalt) }}</p>
                                </div>
                                <div class="clearfix"></div>
                               
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
                                <span class="text">Dokument Anlage/n: </span>
                                @foreach($document->documentUploads as $attachment)
                                    <!--<a target="_blank" href="#{{$attachment->file_path}}" class="">{{basename($attachment->file_path)}}</a><br>-->
                                   <a target="_blank" href="{{ url('download/'.str_slug($document->name).'/'.$attachment->file_path) }}" class="link">{{basename($attachment->file_path)}}</a>
                                   <br><span class="indent"></span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div> <br>
            </div>

            <div class="col-lg-2 btns">
                <a href="{{route('dokumente.edit', $document->id)}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.edit')}} </a>
                <button class="btn btn-primary pull-right">{{ trans('dokumentShow.deactivate') }}</button>
                <button class="btn btn-primary pull-right">{{ trans('dokumentShow.new-version') }}</button>
                <button class="btn btn-primary pull-right">{{ trans('dokumentShow.history') }}</button>
                <button class="btn btn-primary pull-right">{{ trans('dokumentShow.favorite') }}</button>
                <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#kommentieren">{{ trans('dokumentShow.commenting') }}</button>
                <button class="btn btn-primary pull-right">{{ trans('dokumentShow.approve') }}</button>
                <button class="btn btn-primary pull-right">{{ trans('dokumentShow.disapprove') }}</button>
                <button class="btn btn-primary pull-right">{{ trans('dokumentShow.download') }}</button>
                
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
            
                        <form action="">
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
                                <button type="button" class="btn btn-primary">{{ trans('dokumentShow.save') }}</button>
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
