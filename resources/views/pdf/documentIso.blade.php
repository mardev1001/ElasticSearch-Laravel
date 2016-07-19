<!DOCTYPE html>
<html lang="hr">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>@yield("title",'Neptun dokument')</title>
      {!! Html::script(elixir('js/script.js')) !!}
      <link rel="shortcut icon" href="/img/favicon.png">
        <script>
            $(document).ready( function(){
               $('.dummy-divv:gt(0)').each(function(){
                    $(this).attr('style','height: 200px !important');
               }); 
            });
        </script>
            <style>
            body,p,h1,h2,h3,h4,h5{
                font-family: 'Helvetica' !important;
            }
            p{
                font-size: 14px;
                margin-bottom: 25px;
            }
            table {
                margin-left: 0 !important;
                width: 100% !important;
                /*margin-right: 30pt !important;*/
            }
            table td{
                 width: auto !important;
            }
             @page { margin-top: 80px; }
             
            .clearfix{
                clear: both !important;
                height:1px;
            }
            .header,.footer {
                width: 100%;
                position: fixed;
            }
            .header{
                 top: -20px;
                 left: 0;
             
                 clear:both;
            }
             .border-div{
                border-bottom: 1px solid black;
                height: 1px;
                width: 100%;
                /*margin-top:55px;*/
             }
            .header .div-pusher{
                width:60%;
                float: left;
            }
            .header .image-div {
                width:40%;
                float:right;
                margin-right: 80px;
            }
            .parent-pagenum{
                margin-bottom:10px;
                padding-right: 10px;
            }
            .pagenum:before {
                content: counter(page);
            }
            .pull-right{
                text-align: right;
            }
            .div-pusher{
                width:50%;
                padding-left: 30px;
                float: left;
            }
            .footer {
                bottom: 5px;
            }
            
            .first-title.first{
                /*margin-top: 70px;*/
                margin-bottom:0px;
            }
            .first-title.second{
                margin-top: 0;
                /*margin-bottom:50px;*/
            }
             .content-wrapper{
                padding: 0 80px 10px 30px;
            }
            /*#content-:not(:first){*/
            /*    margin-top: 150px;*/
            /*    padding-top: 150px;*/
            /*}*/
            /*.content-wrapper:not(:first){*/
            /*    margin-top: 150px;*/
            /*    padding-top: 150px;*/
            /*}*/
            .document-title-row{
                width: 70%;
                float:left;
            }
            .document-date-row{
                width: 30%;
                float:right;
                font-size: 14px;
                padding-top: 7px;
            }
            .date-div{
                width:100%;
                float:right !important;
                text-align: right;
            }
            .date-div .right-correction{
                margin-right: -5px;
            }
            
            
            .text-upper{
                text-transform: uppercase;
            }
            .bold{
                font-weight: bold;
            }
            .page-num-p{
                text-align:right; 
                font-size:14px;
            }
            .mb30{
                margin-bottom: 30px;
            }
            .mb60{
                margin-bottom: 60px;
            }
            .mb90{
                margin-bottom: 90px;
            }
            
            #absolute{
                 font-size: 10px !important;
                 margin-top: -125px;
                 margin-left: 300px;
            }
            .footer { position: fixed; bottom: 10px; left: 350px; }
            .absolute, .absolute:nth-child(even){
                width:80px;
                margin-top: -125px;
                margin-left: 300px;
                
            }
            .absolute p{
                margin-bottom: 0 !important;
                margin-top: 0 !important;
                text-align: left;
            }
            @page p:first{
                margin-top:300px;
            }
        </style>
    </head>
    <body>
      @include('pdf.headerIso')
      @include('pdf.footerIso')
      <div id="content" >
          <div class="content-wrapper" >
              @if( count( $variants) )
                  @foreach( $variants as $v => $variant)
                      @if( isset($variant->hasPermission) && $variant->hasPermission == true )
                      <div>
                          {!! ($variant->inhalt) !!}
                      </div>
                      @endif
                  @endforeach
              @endif    
          </div>
          
      </div>

     </body>
</html>