<!DOCTYPE html>
<html lang="hr">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>@yield("title",'Neptun dokument')</title>
      <link rel="shortcut icon" href="/img/favicon.png">
        @if( $document->landscape == true)
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
                top: -15px;
            }
            .div-pusher{
                width:50%;
                padding-left:30%;
            }
            .header .div-pusher{
                width:80%;
                padding-left:30%;
            }
            .header .image-div {
                width:20%;
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
            .footer {
                bottom: 5px;
            }
            .pagenum:before {
                content: counter(page);
            }
            .first-title.first{
                margin-top: 40px;
                margin-bottom:0px;
            }
            .first-title.second{
                margin-top: 0;
                margin-bottom:20px;
            }
             .first-title, .content-wrapper{
                padding: 0 80px 10px 30px;
            }
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
            
            .clearfix{
                clear: both !important;
                height:1px;
            }
            .half-width{
                width:50% !important;
                float:left;
            }
            .footer .half-width{
               
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
            table {
                margin-left: 0 !important;
                width: 100% !important;
                /*margin-right: 30pt !important;*/
            }
            table td{
                 width: auto !important;
            }
            #absolute{
                 font-size: 10px !important;
                 margin-top: -125px;
                 margin-left: 300px;
            }
             .footer { position: fixed; bottom: 5px; left: 680px; }
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
        </style><!--end landscape css-->
        @else
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
                top: -15px;
            }
            .div-pusher{
                width:50%;
                padding-left:30%;
            }
            .header .div-pusher{
                width:60%;
                padding-left:30%;
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
            .footer {
                bottom: 5px;
            }
            .pagenum:before {
                content: counter(page);
            }
            .first-title.first{
                margin-top: 70px;
                margin-bottom:0px;
            }
            .first-title.second{
                margin-top: 0;
                margin-bottom:50px;
            }
             .first-title, .content-wrapper{
                padding: 0 80px 10px 30px;
            }
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
            
            .clearfix{
                clear: both !important;
                height:1px;
            }
            .half-width{
                width:50% !important;
                float:left;
            }
            .footer .half-width{
               
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
            table {
                margin-left: 0 !important;
                width: 100% !important;
                /*margin-right: 30pt !important;*/
            }
            table td{
                 width: auto !important;
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
        </style>
        @endif
    </head>
    <body>
     <!-- if you want header on every page  set the include pdf.header here -->
      @include('pdf.footer')
      <div id="content">
           @include('pdf.header')
          <h4 class="first-title first">Per Telefax/per E-Mail</h4>
          <h4 class="first-title second">
            @if( $document->document_type_id == 3 )
              QMR
            @else
                {{ $document->documentType->name }}
            @endif
              @if( $document->document_type_id == 3 )
                  @if( $document->qmr_number != null)
                      {{ $document->qmr_number }}
                  @endif
              @elseif( $document->document_type_id == 4 )
                  @if( $document->iso_category_number != null)
                      {{ $document->iso_category_number }}
                  @endif
              @endif
          </h4>
          <!--<div class="div-pusher"></div>-->
          <div class="content-wrapper">
              <div class="row mb90">
                    <div class="document-title-row">
                        @if( $document->adressat_id != null )
                          <h4 class="document-adressat">{{$document->documentAdressats->name}}</h4>
                        @endif
                    </div>
                  <div class="document-date-row">
                      <div class="date-div"><p>
                          @if( $document->created_at != null)
                              <span class="right-correction">{{$document->created_at}}</span>
                          @endif
                          <br/>
                          {{ $document->user->short_name }}
                          </p></div>
                  </div>
                  <div class="clearfix"></div>
                </div><!--end row-->  
              <div class="clearfix"></div>
              <div class="row">
                  <h4 class="document-title">{{$document->name_long}}</h4>
              </div>
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