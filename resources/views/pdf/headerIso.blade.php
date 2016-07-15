<div class="header">
    <div class="div-pusher">       
    <p>{{$document->name_long}}</p>
       
    </div>
    <div class="image-div">
         @if( $document->isoCategories != null) 
            <p>{{$document->isoCategories->name }} 
             @if( $document->document_type_id == 4 )
              @if( $document->iso_category_number != null)
                / Kapitel-Nr:   {{ $document->iso_category_number }}
              @endif
            @endif
        </p>
        @endif
    </div>
    <div class="clearfix"></div>
</div>