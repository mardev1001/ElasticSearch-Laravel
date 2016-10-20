<style>
        .header{
            z-index:9999999999999999 !Important;
            margin-top: 150px;
        }
       .div-pusher{
                width:50%;
                padding-left:30%;
                   z-index:9999999999999999 !Important;
            }
            .header .div-pusher{
                width:60%;
                padding-left:30%;
                   z-index:9999999999999999 !Important;
            }
            .header .image-div {
                width:40%;
                float:right !important;
                padding-left:50px;
                height:auto;
            }
            .header .image-div img{
               margin-left:0px;
               width:100%;
               height:auto;
               display:block;
            }
  
</style>
<div id="header" class="header">
    <div class="div-pusher">
        <p>empty string
        </p>
    </div>
    <div class="image-div">
        testiranje
        <img src={{url("/img/Neptun-document-logo.jpg")}} alt="Neptun logo"/>
    </div>
</div>