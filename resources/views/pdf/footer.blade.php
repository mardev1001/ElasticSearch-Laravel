<div class="footer">
   
        <div id="absolute" class="absolute">
            <script type="text/php">
                 $text = '{PAGE_NUM}/{PAGE_COUNT}';
                 $font = Font_Metrics::get_font("Verdana", "normal");
                 $y = $pdf->get_height() - 24;
                 $x = $pdf->get_width()/2 - Font_Metrics::get_text_width('1/1', $font,9);
                 $pdf->page_text($x, $y, $text, $font, 9);
            </script>
            <p>Sitz Taufkirchen</p>
            <p>Amtsgericht</p>
            <p>München</p>
            <p>HRB 74557</p>
            <br/>
            <p>Geschäftsführerin</p>
            <p>Bettina Engel</p>
            <br/>
            <p>Commerzbank AG</p>
            <p>München</p>
            <p>BLZ 700 800 00</p>
            <p>Kto. 603 366 000</p>
        </div>
        <!--<p>-->
        <!--    Neptun GmbH <br/>-->
        <!--    Revisionsstand 05<br/>-->
        <!--    Taufkirchen, {{ $dateNow }}-->
        <!--</p>-->

</div>