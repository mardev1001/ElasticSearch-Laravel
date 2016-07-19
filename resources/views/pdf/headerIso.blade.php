<div id="header" class="header">
    <div class="div-pusher">       
    <p>{{$document->name_long}}</p>
    </div>
    <div class="image-div">
        <div class="pull-right" style="float:left; width:70%;">
         @if( $document->isoCategories != null) 
            <p>{{$document->isoCategories->name }} 
             @if( $document->document_type_id == 4 )
              @if( $document->iso_category_number != null)
                / Kapitel-Nr:   {{ $document->iso_category_number }}
              @endif
            @endif
        </p>
         <p> @if( $document->iso_category_number != null) {{ $document->iso_category_number }} @endif  
        <span class="pagenum"></span></p>
        @endif
        </div>
    </div>
  <div class="border-div"></div>
</div>