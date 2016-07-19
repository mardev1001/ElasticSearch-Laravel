<div class="header"  style="">
    
    <div class="div-pusher">       
        <p>{{$document->name_long}}</p>
    </div>
        <div class="image-div">
        <div class="pull-right" style="">
         @if( $document->isoCategories != null) 
            <p>
                {{ $document->isoCategories->name }}
                @if( $document->iso_category_number != null)
                    / Kapitel-Nr:   {{ $document->iso_category_number }}
                @endif
            </p>
         <p class="parent-pagenum"> <span class="pagenum"></span></p>
        @endif
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="border-div"></div>
</div>
<div class="dummy-div" style="height: 100px"></div>