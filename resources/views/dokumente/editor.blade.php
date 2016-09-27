@section('page-title') {{ trans('controller.create') }} @stop
<h3 class="title">{{ trans('controller.editor') }}</h3>


<input type="hidden" name="model_id" value="{{$data->id}}" />
@if($data->landscape == true)
    <input type="hidden" class="document-orientation" name="document-orientation" value="landscape" />
@endif
<!--<div class="box-wrapper">-->
<!--    <div class="box">-->

        <div class="row">
            <!-- input box-->
            <div class="col-lg-5"> 
                <div class="form-group">
                    {!! ViewHelper::setSelect($adressats,'adressat_id',$data,old('adressat_id'),
                            trans('documentForm.adressat'), trans('documentForm.adressat') ) !!}
                </div>   
            </div><!--End input box-->
            
            <!-- input box-->
            <div class="col-lg-5"> 
                <div class="form-group">
                    {!! ViewHelper::setArea('betreff',$data,old('betreff'),trans('documentForm.subject'), trans('documentForm.subject'), false,
                    array(), array(), false, true ) !!}
                </div>   
            </div><!--End input box-->
            
            <div class="clearfix"></div>
            
            <!-- input box-->
            <div class="col-lg-3"> 
                <div class="form-group checkbox-form-group">
                    {!! ViewHelper::setCheckbox('show_name',$data,old('show_name'),trans('documentForm.showName') ) !!}
                </div>   
            </div><!--End input box-->
            
    <!--    </div>-->
    <!--</div>-->
</div>
<div class="clearfix"></div>
<div class="row">
    <div class="parent-tabs col-xs-12 col-md-12">
    <hr/>
      <!-- Tab panes -->
    <a href="#" class="btn btn-primary add-tab"><span class="fa fa-plus"></span> Neue Variante</a>
   
    <div class="pull-right">
        <button href="#" class="btn btn-primary preview" name="preview" value="preview" type="submit">Seiten Vorschau</button>
        <button href="#" class="btn btn-primary preview" name="pdf_preview"name="preview" value="pdf_preview" type="submit">PDF Vorschau</button>
        <input type="hidden" name="current_variant" value="1" />
    </div>
    
    <ul class="nav nav-tabs" id="tabs">
       @if( count($data->editorVariantOrderBy) )
           @foreach( $data->editorVariantOrderBy as $variant)
               <li data-variant="{{$variant->variant_number}}"><a href="#variation{{$variant->variant_number}}" data-toggle="tab">Variante {{$variant->variant_number}}
               <span class="fa fa-close remove-editor" data-delete-variant="{{ $variant->variant_number }}"></span> </a></li>
           @endforeach
       @endif
    </ul>
    
    <div class="tab-content">
       @if( count($data->editorVariant) ) 
           @foreach( $data->editorVariant as $variant)
               <div class="tab-pane " id="variation{{$variant->variant_number}}" data-id="{{$variant->variant_number}}">
                   <div class="variant" id="variant-{{$variant->variant_number}}" >
                       {!!($variant->inhalt)!!}
                   </div>
                </div>
           @endforeach
       @endif
    </div>
  </div>
</div>


	
<div class="clearfix"></div>
  @if( count($data->editorVariant) ) 
      @section('script')
        <script type="text/javascript">
            $(document).ready(function(){
                  @if( isset($previewUrl) && $previewUrl != '')
                    var url="{{$previewUrl}}", win = window.open(url, '_blank');
                        win.focus();
                  @endif
              if ($('.editable').length) {
                    var counter = 0;
                    $('.editable').each(function() {
                        counter++;
                        if ($(this).data('id'))
                            $(this).attr('id', 'variant-'+$(this).data('id'));
                        else
                            $(this).attr('id', 'variant-' + counter);
                        tinymce.init({
                            selector: '.editable',
                            skin_url: '/css/style',
                            width: 794,
                            height: 1122,
                            removed_menuitems: 'newdocument',
                        });
                    });
              }
              	if( $('.nav-tabs li.active').length < 1 ){
              	    $('.nav-tabs li').first().addClass('active'); 
              	    $('.tab-content .tab-pane').first().addClass('active'); 
      	        }
            });//end document ready
        </script>
      @stop
    @else
        @section('script')
            <script type="text/javascript">
                $(document).ready(function(){
                    @if( isset($previewUrl) && $previewUrl != '')
                        var url="{{$previewUrl}}", win = window.open(url, '_blank');
                            win.focus();
                    @endif
                    if ($('.editable').length) {
                    var counter = 0;
                    $('.editable').each(function() {
                        counter++;
                        if ($(this).data('id'))
                            $(this).attr('id', 'variant-'+$(this).data('id'));
                        else
                            $(this).attr('id', 'variant-' + counter);
                        tinymce.init({
                            selector: '.editable',
                            skin_url: '/css/style',
                            plugins:[ "image table" ],
                            toolbar1: " | undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect  ",
                            body_class: classes,
                            width: docWidth,
                            height: docHeight, 
                            removed_menuitems: 'newdocument',
                            elementpath: false,
                        });
                    });
               }    
                    
                   if( $('#variant-1').length == 0 ){
              	        $('.add-tab').click();
              	    }
                  	
                  	if( $('.nav-tabs li.active').length < 1 ){
                  	    $('.nav-tabs li').first().addClass('active'); 
                  	    $('.tab-content .tab-pane').first().addClass('active'); 
          	        }
                });//end document ready
               
            </script>
        @stop
    @endif
    @if( isset( $data->document_type_id ) )
           @section('preScript')
               <!-- variable for expanding document sidebar-->
               <script type="text/javascript">
                    var documentType = "{{ $data->documentType->name}}";
                   
                      
               </script>
               
               <!--patch for checking iso category document-->
                @if( isset($data->isoCategories->name) )
                    <script type="text/javascript">   
                        if( documentType == 'ISO Dokumente')
                            var isoCategoryName = '{{ $data->isoCategories->name}}';
                    </script>
                @endif
               <!-- End variable for expanding document sidebar-->
           @stop
       @endif