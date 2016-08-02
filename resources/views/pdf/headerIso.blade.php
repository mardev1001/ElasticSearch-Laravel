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
                    / Kapitel-Nr:   {{ $document->iso_category_number }}@if( $document->additional_letter ){{ $document->additional_letter }} @endif
                @endif
            </p>
         <p class="parent-pagenum"> 
                @if( $document->landscape == 1)
                     <script type="text/php">
                         $icn = '{{$document->iso_category_number}}';
                         $text = 'Seite '.$icn.'-{PAGE_NUM} von {PAGE_COUNT}';
                         
                         $font = Font_Metrics::get_font("arial", "italic");
                         $pdf->page_text(640, 40, $text, $font, 9);
                    </script>
                @else
                     <script type="text/php">
                         $icn = '{{$document->iso_category_number}}';
                         $text = 'Seite '.$icn.'-{PAGE_NUM} von {PAGE_COUNT}';
                         
                         $font = Font_Metrics::get_font("arial", "italic");
                         $pdf->page_text(390, 50, $text, $font, 9);
                    </script>
                @endif
            
         </p>
        @endif
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="border-wrapper">
        <div class="border-div"></div>
    </div>
    
</div>
<div class="dummy-div" style=""></div>