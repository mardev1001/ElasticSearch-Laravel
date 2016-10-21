<style type="text/css">
    #footer{
        bottom: 5px; 
        right: 0;        
                
    }
    #footer p{
        text-align: right;
        margin-right: -100px;
        color: #808080;
        margin-bottom: 0px !important;
        padding-bottom: 0px !important;
        margin-top: 0 !important;
        padding: 3px 135px 3px 0 !important;
        font-size: 12px;
        line-height: 12px; 
    }
</style>
<div id="footer" class="footer">
       <p>&copy; Neptun GmbH</p>
       <p>Revisionsstand 05</p>
       <p>Taufkirchen, {{ $dateNow }}</p>
       <script type="text/php">
                 $text = '{PAGE_NUM}/{PAGE_COUNT}';
                 $font = Font_Metrics::get_font("Verdana", "normal");
                 $y = $pdf->get_height() - 24;
                 $x = $pdf->get_width()/2 - Font_Metrics::get_text_width('1/1', $font,9);
                 $pdf->page_text($x, $y, $text, $font, 9);
            </script>
</div>