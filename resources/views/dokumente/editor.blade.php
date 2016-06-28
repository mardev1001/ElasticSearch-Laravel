@section('page-title') {{ trans('controller.create') }} @stop
<h3 class="title">{{ trans('controller.editor') }}</h3>
<input type="hidden" name="model_id" value="{{$data->id}}" />
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
                    {!! ViewHelper::setInput('betreff',$data,old('betreff'),trans('documentForm.subject') , 
                           trans('documentForm.subject') , false  ) !!}
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
        <a href="#" class="btn btn-primary">Seiten Vorschau</a>
        <a href="#" class="btn btn-primary">PDF Vorschau</a>
    </div>
    
    <ul class="nav nav-tabs" id="tabs">
       @if( count($data->editorVariantOrderBy) ) 
           @foreach( $data->editorVariantOrderBy as $variant)
               <li data-variant="{{$variant->variant_number}}"><a href="#variant{{$variant->variant_number}}" data-toggle="tab">Variante {{$variant->variant_number}}
               <span class="fa fa-close remove-editor" data-delete-variant="{{ $variant->variant_number }}"></span> </a></li>
           @endforeach
       @endif
    </ul>
    
    <div class="tab-content">
       @if( count($data->editorVariant) ) 
           @foreach( $data->editorVariant as $variant)
               <div class="tab-pane" id="variant{{$variant->variant_number}}">
                   <div class="variant" id="variant-{{$variant->variant_number}}">
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
               $('.editable').each(function(){
              	    var id=$(this).data('id');
              	 }); 
              	tinymce.init({selector:'.editable',removed_menuitems: 'newdocument',});
              	
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