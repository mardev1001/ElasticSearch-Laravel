<style>
@page { margin: 0px:
    z-index: 0;
}
    #footer { position: fixed;
    bottom: 0px;
    right: 150px;
    height: 225px;
    /*margin-top: -150px;*/
    /*margin-left: 350px;*/
    z-index: 999999999;
    }
      #absolute{
                     font-size: 10px !important;
                     /*margin-top: -325px;
                     margin-left: 300px;*/
                     z-index:999999999;
                }
     
        .absolute, .absolute:nth-child(even){
            width:115px;
            margin-top: -25px;
            margin-left: 475px;
            color: #808080;
            z-index:999999999;
            background: red;
            font-size:10px;
            
        }
        .absolute p{
            margin-bottom: 0 !important;
            margin-top: 0 !important;
            text-align: left;
        }
    
</style>
<div id="footer" class="footer" style="position:fixed !Important; top:100px!Important; left:100px!Important; width:100px!Important; z-index:999999;">
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