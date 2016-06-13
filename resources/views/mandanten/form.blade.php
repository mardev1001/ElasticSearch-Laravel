@section('page-title')
    @if( Request::is('*/edit') )
        Mandanten bearbeiten
    @else
        Mandanten anlegen
    @endif
@stop

<div class="col-xs-12 box-wrapper">
    <h3 class="title">Basis Infos</h3>
    <div class="box">
        <div class="row">
            <!-- input box-->
            <div class="col-lg-4">
                <div class="form-group">
                    {!! ViewHelper::setInput('name',$data,old('name'),trans('mandantenForm.name') , 
                    trans('mandantenForm.name') , false  ) !!}
                </div>
            </div><!--End input box-->
        
            <!-- input box-->
            <div class="col-lg-4 checkbox-form-group">
                {!! ViewHelper::setCheckbox('hauptstelle',$data,old('hauptstelle'),trans('mandantenForm.hauptstelle'), false) !!}
            </div><!--End input box-->
        
            <div class="clearfix"></div>
        
            <!-- input box-->
            <div class="col-lg-4 "> <!-- add class hidden when activating js hidden -->
                <div class="form-group">
                    {!! ViewHelper::setInput('mandant_number',$data,old('mandant_number'),trans('mandantenForm.mandantenNum') , 
                     trans('mandantenForm.mandantenNum') , false  ) !!}
                </div>
            </div><!--End input box-->
        
            <!-- input box-->
            <div class="col-lg-4 select-mandants">
                <div class="form-group">
                    {!! ViewHelper::setSelect($mandantsAll, 'mandant_id_hauptstelle', $data, old('mandant_id_hauptstelle'), trans('mandantenForm.num-hauptstelle')) !!}
                </div>
            </div><!--End input box-->
        </div>
    </div>
</div>
<div class="clearfix"></div>



@if( Request::is('*/edit') )
    <br>
    <div class="box-wrapper">
        <h3 class="title">Zusatzdaten</h3>
        <div class="box">
            <div class="row">
                <!-- input box-->
                <div class="col-lg-4"> <!-- add class hidden when activating js hidden -->
                    <div class="form-group">
                        {!! ViewHelper::setInput('kurzname',$data,old('kurzname'),trans('mandantenForm.kurzname') ) !!}
                    </div>
                </div><!--End input box-->
                <!-- input box-->
                <div class="col-xs-6 col-lg-6">
                    <div class="checkbox-form-group">
                        {!! ViewHelper::setCheckbox('rights_wiki',$data,old('rights_wiki'),trans('mandantenForm.rightsWiki') ) !!}
                        {!! ViewHelper::setCheckbox('rights_admin',$data,old('rights_admin'),trans('mandantenForm.redaktion') ) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"><br/></div>

    <div class="box-wrapper">
        <h3 class="title">Adresse</h3>
        <div class="box">
            <div class="row">
                
                <div class="col-lg-8">
                    
                    <div class="row">
                        
                        <!-- input box-->
                        <div class="col-lg-12">
                            <div class="form-group">
                                {!! ViewHelper::setInput('adresszusatz',$data,old('adresszusatz'),trans('mandantenForm.adresszusatz') ) !!}
                
                            </div>
                        </div><!--End input box-->
                
                        <!-- input box-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! ViewHelper::setInput('strasse',$data,old('strasse'),trans('mandantenForm.strasse') ) !!}
                            </div>
                        </div><!--End input box-->
                
                        <!-- input box-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! ViewHelper::setInput('hausnummer',$data,old('hausnummer'),trans('mandantenForm.hausnummer') ) !!}
                            </div>
                        </div><!--End input box-->
                
                        <!-- input box-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! ViewHelper::setInput('plz',$data,old('plz'),trans('mandantenForm.plz') ) !!}
                            </div>
                        </div><!--End input box-->
                
                        <!-- input box-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! ViewHelper::setInput('ort',$data,old('ort'),trans('mandantenForm.ort') ) !!}
                            </div>
                        </div><!--End input box-->
                
                        <!-- input box-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! ViewHelper::setInput('telefon',$data,old('telefon'),trans('mandantenForm.telefon') ) !!}
                            </div>
                        </div><!--End input box-->
                
                        <!-- input box-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! ViewHelper::setInput('kurzwahl',$data,old('kurzwahl'),trans('mandantenForm.kurzwahl') ) !!}
                            </div>
                        </div><!--End input box-->
                
                        <!-- input box-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! ViewHelper::setInput('fax',$data,old('fax'),trans('mandantenForm.fax') ) !!}
                            </div>
                        </div><!--End input box-->
                
                        <!-- input box-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! ViewHelper::setInput('email',$data,old('email'),trans('mandantenForm.email') ) !!}
                            </div>
                        </div><!--End input box-->
                
                        <!-- input box-->
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! ViewHelper::setInput('website',$data,old('website'),trans('mandantenForm.website') ) !!}
                            </div>
                        </div><!--End input box-->
                        
                    </div>
                    
                </div>
                
                <!--logo box-->
                <div class="col-lg-4">
                    <input type="file" id="image-upload" name="file"/> <br/>
                    
                    @if($data->logo)
                        <img class="img-responsive" id="image-preview" src="{{url('/files/pictures/mandants/'. $data->logo)}}"/>
                    @else
                        <img class="img-responsive" id="image-preview" src="{{url('/img/mandant-default.png')}}"/>
                    @endif
                    
                </div>
                
            </div>
        </div>
    </div>

    <div class="clearfix"><br/></div>

    <div class="box-wrapper">
        <h3 class="title">Geschäftsführer-Informationen</h3>
        <div class="box">
            <div class="row">
        
                <!-- input box-->
                <div class="col-lg-3">
                    <div class="form-group">
                        {!! ViewHelper::setUserSelect($mandantUsers, 'geschaftsfuhrer', $data, old('geschaftsfuhrer'), trans('mandantenForm.geschaftsfuhrer') )  !!}
                    </div>
                </div><!--End input box-->
                
                <!-- input box-->
                <div class="col-lg-3">
                    <div class="form-group">
                        {!! ViewHelper::setInput('geschaftsfuhrer_infos', $data, old('geschaftsfuhrer_infos'), trans('mandantenForm.geschaftsfuhrer_infos') ) !!}
                    </div>
                </div><!--End input box-->
                
                <!-- input box-->
                <div class="col-lg-2">
                    <div class="form-group">
                        {!! ViewHelper::setInput('geschaftsfuhrer_von', $data, old('geschaftsfuhrer_von'), trans('mandantenForm.von'), trans('mandantenForm.von'), false, '', ['datetimepicker']) !!}
                    </div>
                </div><!--End input box-->
                
                <!-- input box-->
                <div class="col-lg-2">
                    <div class="form-group">
                        {!! ViewHelper::setInput('geschaftsfuhrer_bis', $data, old('geschaftsfuhrer_bis'), trans('mandantenForm.bis'), trans('mandantenForm.bis'), false, '', ['datetimepicker']) !!}
                    </div>
                </div><!--End input box-->
                
                <!-- input box-->
                <div class="col-lg-2 custom-input-group-btn">
                    <button type="button" class="btn btn-primary history-add"> {{ trans('mandantenForm.add') }} </button>
                </div><!--End input box-->
        
                <div class="clearfix"></div> <br>
                
                <!-- input box-->
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! ViewHelper::setArea('geschaftsfuhrer_history', $data, old('geschaftsfuhrer_history'),trans('mandantenForm.managerHistory'),trans('mandantenForm.managerHistory'), false, null, null, true ) !!}
                    </div>
                </div><!--End input box-->
        
            </div>
        </div>
    </div>


    <div class="clearfix"><br/></div>

   
    <div class="box-wrapper">
        <h3 class="title">Zusätzliche Informationen</h3>
        <div class="box">
            <div class="row">
        
                <!-- input box-->
                <div class="col-lg-8 ">
                    <div class="form-group">
                        {!! ViewHelper::setInput('prokura',$data->mandantInfo,old('prokura'),trans('mandantenForm.procuration') ) !!}
                    </div>
                </div><!--End input box-->
        
                <div class="clearfix"></div>
        
                <!-- input box-->
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! ViewHelper::setInput('betriebsnummmer',$data->mandantInfo,old('betriebsnummmer'),trans('mandantenForm.operationNum') ) !!}
                    </div>
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! ViewHelper::setInput('handelsregister',$data->mandantInfo,old('handelsregister'),trans('mandantenForm.commercialRegister') ) !!}
                    </div>
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! ViewHelper::setArea('handelsregister_sitz',$data->mandantInfo,old('handelsregister_sitz'),trans('mandantenForm.hrSitz') ) !!}
                    </div>
                </div><!--End input box-->
        
                <div class="clearfix"></div>
        
                <!-- input box-->
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! ViewHelper::setInput('steuernummer', $data->mandantInfo, old('steuernummer'), trans('mandantenForm.taxNumber') ) !!}
                    </div>
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! ViewHelper::setInput('ust_ident_number',$data->mandantInfo,old('ust_ident_number'),trans('mandantenForm.userIdentNumber') ) !!}
                    </div>
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! ViewHelper::setArea('zausatzinfo_steuer',$data->mandantInfo,old('zausatzinfo_steuer'),trans('mandantenForm.infoControll') ) !!}
                    </div>
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! ViewHelper::setInput('berufsgenossenschaft_number',$data->mandantInfo, old('berufsgenossenschaft_number'),trans('mandantenForm.berufsgenossen'),trans('mandantenForm.mitgliedsnr') ) !!}
                    </div>
                </div><!--End input box-->
        
                <div class="clearfix"></div>
        
                <!-- input box-->
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! ViewHelper::setArea('berufsgenossenschaft_zusatzinfo',$data->mandantInfo,old('berufsgenossenschaft_zusatzinfo'),trans('mandantenForm.infosBg') ) !!}
                    </div>
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-lg-4">
                    <div class="form-group">
                        {!! ViewHelper::setInput('erlaubniss_gultig_ab', $data->mandantInfo, old('erlaubniss_gultig_ab'), trans('mandantenForm.permissions'), trans('mandantenForm.permissionsPlaceholder'), false, '', ['datetimepicker'] ) !!}
                    </div>
                </div><!--End input box-->
        
                <div class="clearfix"></div>
        
                <!-- input box-->
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! ViewHelper::setArea('erlaubniss_gultig_von',$data->mandantInfo,old('erlaubniss_gultig_von'),trans('mandantenForm.permissionIssuer') ) !!}
                    </div>
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-lg-4 ">
                    <div class="form-group">
                        {!! ViewHelper::setInput('geschaftsjahr',$data->mandantInfo,old('geschaftsjahr'),trans('mandantenForm.fiscalYear'),trans('mandantenForm.fiscalYearPlaceholder') ) !!}
                    </div>
                </div><!--End input box-->
        
                <div class="clearfix"></div>
        
                <!-- input box-->
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! ViewHelper::setArea('geschaftsjahr_info',$data->mandantInfo,old('geschaftsjahr_info'),trans('mandantenForm.aboutFiscal') ) !!}
                    </div>
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! ViewHelper::setArea('bankverbindungen',$data->mandantInfo,old('bankverbindungen'),trans('mandantenForm.bank') ) !!}
                    </div>
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! ViewHelper::setArea('info_wichtiges',$data->mandantInfo,old('info_wichtiges'),trans('mandantenForm.importantInfo') ) !!}
                    </div>
                </div><!--End input box-->
        
                <!-- input box-->
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! ViewHelper::setArea('info_sonstiges',$data->mandantInfo,old('info_sonstiges'),trans('mandantenForm.other') ) !!}
                    </div>
                </div><!--End input box-->
            </div>
          </div>
        </div>
        <div class="clearfix"></div> 
         
             
            
        @endif
        @if( Request::is('*/create') )
            @section('beforeButtons')
                <div class="button-box box-wrapper">
            @stop
        @elseif( Request::is('*/edit') )
            @section('beforeButtons')
                <div class="button-box box-wrapper">
            @stop
        @endif
     
     @if( Request::is('*/edit') )
         @section('closingElementsAfterForm')
                @include('partials.mandantStaticRoles')
        @stop
     @endif
 