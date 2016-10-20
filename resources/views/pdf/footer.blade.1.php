<style>
@page { margin-right: 100px:
    
}
    #footer { 
    position: fixed;
    top: 200px;
    right: 0px;
    height: 100px !important;
    margin-top: -400px;
      /*margin-left:500px;*/
    z-index: 99999999;
    background: red;
    font-size: 10px;
    }
    
        /*.absolute, .absolute:nth-child(even){*/
        /*    width:115px;*/
            /*margin-top: -275px;*/
        /*    margin-left:300px;*/
        /*    color: #808080;*/
        /*    z-index:999999999;*/
        /*    background: blue;*/
        /*    font-size:10px;*/
        /*    height:100;*/
            
        /*}*/
        .absolute p{
            margin-bottom: 0 !important;
            margin-top: 0 !important;
            text-align: left;
        }
    
</style>
<div id="footer" class="footer" >
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
   <div  class="absolute">
             <script type="text/php">
            //      $text = '{PAGE_NUM}/{PAGE_COUNT}';
            //      $font = Font_Metrics::get_font("Verdana", "normal");
            //      $y = $pdf->get_height() - 24;
            //      $x = $pdf->get_width()/2 - Font_Metrics::get_text_width('1/1', $font,9);
            //      $pdf->page_text($x, $y, $text, $font, 9);
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