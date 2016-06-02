<h3 class="title">Dokumente anlegen - Daten Eingabe - Dokumentenart - Editor</h3>

<input type="hidden" name="model_id" value="{{$data->id}}" />

<div class="row">
    <!-- input box-->
    <div class="col-lg-3"> 
        <div class="form-group">
            {!! ViewHelper::setSelect($adressats,'adressat_id',$data,old('adressat_id'),
                    trans('documentForm.adressat'), trans('documentForm.adressat'), true ) !!}
        </div>   
    </div><!--End input box-->
    
    <!-- input box-->
    <div class="col-lg-3"> 
        <div class="form-group">
            <br>
            {!! ViewHelper::setCheckbox('show_name',$data,old('show_name'),trans('documentForm.showName') ) !!}
        </div>   
    </div><!--End input box-->
</div>

<div class="clearfix"></div>
<div class="row">
    <div class="parent-tabs col-xs-12 col-md-12">
    <hr/>
      <!-- Tab panes -->
    <a href="#" class="btn btn-primary add-tab"><span class="fa fa-plus"></span> Neue Variante</a>
    <br><br>
    <ul class="nav nav-tabs" id="tabs">
       @if( count($data->editorVariant) ) 
           @foreach( $data->editorVariant as $variant)
               <li><a href="#variant{{$variant->variant_number}}" data-toggle="tab">Variation {{$variant->variant_number}}</a></li>
           @endforeach
       @endif
    </ul>
    
    <div class="tab-content">
       @if( count($data->editorVariant) ) 
           @foreach( $data->editorVariant as $variant)
               <div class="tab-pane" id="variant{{$variant->variant_number}}">
                   <div id="variant-{{$variant->variant_number}}" class="editable">
                       {{strip_tags($variant->inhalt)}}
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
              	tinymce.init({selector:'.editable'});
              	if( $('.nav-tabs li.active').length < 1 ){
              	    $('.nav-tabs li').first().addClass('active'); 
              	    $('.tab-content .tab-pane').first().addClass('active'); 
      	        }
            });//end document ready
        </script>
      @stop
  @endif