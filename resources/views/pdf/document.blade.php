<!DOCTYPE html>
<html lang="hr">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>@yield("title",'Neptun dokument')</title>
      <link rel="shortcut icon" href="/img/favicon.png">
    <style>
        body,p,h1,h2,h3,h4,h5{
            font-family: 'Arial, Helvetica, sans-serif';
        }
        p{
            font-size: 14px;
            margin-bottom: 25px;
        }
        .header,
        .footer {
            width: 100%;
            position: fixed;
        }
        .header {
            top: 0px;
        }
        .div-pusher{
            width:50%;
            padding-left:30%;
        }
        .header .image-div {
            width:50%;
            float:right !important;
            padding-left:50px;
        }
        .header .image-div img{
           margin-left:80px;
        }
        .footer {
            bottom: 0px;
        }
        .pagenum:before {
            content: counter(page);
        }
        .first-title{
            margin-bottom:50px;
        }
         .first-title, .content-wrapper{
            padding: 0 30px;
        }
        .date-div{
            width:100%;
            float:right !important;
            text-align: right;
            padding-right: 30px; /*same as content wrapper*/
        }
        .clearfix{
            clear: both;
        }
        .half-width{
            width:50% !important;
            float:left;
        }
        .footer .half-width{
            font-size: 12px;
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
    </style>
    </head>
    <body>
     <!-- if you want header on every page  set the include pdf.header here -->
      @include('pdf.footer')
      <div id="content">
           @include('pdf.header')
          <h2 class="first-title">{{$document->name_long}}</h2>
          <!--<div class="div-pusher"></div>-->
          <div class="content-wrapper">
              <div class="date-div"><p>{{$document->created_at}}</p></div>
              <div class="clearfix"></div>
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