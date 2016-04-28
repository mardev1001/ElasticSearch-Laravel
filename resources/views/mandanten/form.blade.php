        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('name',$data,old('name'),trans('documentForm.name') , 
               trans('documentForm.name') , true  ) !!}
            </div>   
        </div><!--End input box-->
           
        <!-- input box-->
        <div class="col-xs-3 col-lg-3">
            {!! ViewHelper::setCheckbox('hauptstelle',$data,old('hauptstelle'),trans('documentForm.hauptstelle') ) !!}
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
        <!-- input box-->
        <div class="col-lg-3 visible-yes"> <!-- add class hidden when activating js hidden -->
            <div class="form-group">
              {!! ViewHelper::setInput('mandantenNum',$data,old('name'),trans('documentForm.mandantenNum') , 
               trans('documentForm.mandantenNum') , true  ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'asasasasasasa',$data,old('asasasasa'),
                trans('documentForm.anschrift') )  !!}
                
            </div>   
        </div><!--End input box-->
      
    <div class="clearfix"></div>



********* this will be wrapped in edit statement
    <h3>Zusatzdaten</h3>
  
        <!-- input box-->
        <div class="col-lg-3"> <!-- add class hidden when activating js hidden -->
            <div class="form-group">
                {!! ViewHelper::setInput('kurzname',$data,old('kurzname'),trans('documentForm.kurzname') ) !!}
            </div>   
        </div><!--End input box-->
        <!-- input box-->
        <div class="col-xs-6 col-lg-6">
            {!! ViewHelper::setCheckbox('rights_wiki',$data,old('rights_wiki'),trans('documentForm.rightsWiki') ) !!}
            
            {!! ViewHelper::setCheckbox('rights_admin',$data,old('rights_admin'),trans('documentForm.redaktion') ) !!}
        </div>
        
        <div class="clearfix"></div>
       
       <h3>Adresse</h3>
       
       <!-- input box-->
        <div class="col-lg-6">
            <div class="form-group">
                {!! ViewHelper::setInput('adresszusatz',$data,old('adresszusatz'),trans('documentForm.adresszusatz') ) !!}
              
            </div>   
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('strasse',$data,old('strasse'),trans('documentForm.strasse') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('hausnummer',$data,old('hausnummer'),trans('documentForm.hausnummer') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('plz',$data,old('plz'),trans('documentForm.plz') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('ort',$data,old('ort'),trans('documentForm.ort') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('telefon',$data,old('telefon'),trans('documentForm.telefon') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('kurzwahl',$data,old('kurzwahl'),trans('documentForm.kurzwahl') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('fax',$data,old('fax'),trans('documentForm.fax') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('email',$data,old('email'),trans('documentForm.email') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('website',$data,old('website'),trans('documentForm.website') ) !!}
            </div>   
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
        <h3>Geschäftsführer Infos</h3>
         
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                  {!! ViewHelper::setSelect($collections,'asasasasasasa',$data,old('asasasasa'),
                trans('documentForm.geschaftsfuhrer') )  !!}
            </div>   
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
          
       <!-- input box-->
        <div class="col-lg-3">
            <button type="button" class="btn btn-primary">{{  trans('documentForm.historyAddButton')  }}</button>
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        <br/>
         <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('geschaftsfuhrer_history',$data,old('geschaftsfuhrer_history'),trans('documentForm.managerHistory') ) !!}
            </div>   
        </div><!--End input box-->
        

        <div class="clearfix"></div>
        
        <h3>Zusätzliche Infos</h3>
         
       <!-- input box-->
        <div class="col-lg-6">
            <div class="form-group">
                {!! ViewHelper::setInput('prokura',$data,old('prokura'),trans('documentForm.procuration') ) !!}
            </div>   
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('betriebsnummmer',$data,old('betriebsnummmer'),trans('documentForm.operationNum') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('handelsregister',$data,old('handelsregister'),trans('documentForm.commercialRegister') ) !!}
            </div>   
        </div><!--End input box-->

        <div class="clearfix"></div>
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('zausatzinfo_steuer',$data,old('zausatzinfo_steuer'),trans('documentForm.infoControll') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('berufsgenossenschaft_number',$data,old('berufsgenossenschaft_number'),trans('documentForm.berufsgenossen') ) !!}
            </div>   
        </div><!--End input box-->

        <div class="clearfix"></div>
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('berufsgenossenschaft_zusatzinfo',$data,old('berufsgenossenschaft_zusatzinfo'),trans('documentForm.infosBg') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('erlaubniss_gultig_ab',$data,old('erlaubniss_gultig_ab'),trans('documentForm.permissions') ) !!}
            </div>   
        </div><!--End input box-->

        <div class="clearfix"></div>
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('erlaubniss_gultig_von',$data,old('erlaubniss_gultig_von'),trans('documentForm.permissionIssuer') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('heschaftsjahr',$data,old('heschaftsjahr'),trans('documentForm.fiscalYear') ) !!}
            </div>   
        </div><!--End input box-->

        <div class="clearfix"></div>
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('heschaftsjahr_info',$data,old('heschaftsjahr_info'),trans('documentForm.fiscalYear') ) !!}
            </div>   
        </div><!--End input box-->
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('bankverbindungen',$data,old('bankverbindungen'),trans('documentForm.bank') ) !!}
            </div>   
        </div><!--End input box-->
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                    {!! ViewHelper::setArea('info_wichtiges',$data,old('info_wichtiges'),trans('documentForm.importantInfo') ) !!}
            </div>   
        </div><!--End input box-->
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('info_sonstiges',$data,old('info_sonstiges'),trans('documentForm.other') ) !!}
            </div>   
        </div><!--End input box-->
        
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'steuernummer_lohn',$data,old('steuernummer_lohn'),
                trans('documentForm.wage'), trans('documentForm.selectEmployees')  ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'mitarbeiter_finanz_id',$data,old('mitarbeiter_finanz_id'),
                trans('documentForm.finantialAcc'), trans('documentForm.selectEmployees') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'mitarbeiter_edv_id',$data,old('mitarbeiter_edv_id'),
                trans('documentForm.fieldService'), trans('documentForm.selectEmployees') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'mitarbeiter_vertrieb_id',$data,old('mitarbeiter_vertrieb_id'),
                trans('documentForm.distribution'), trans('documentForm.selectEmployees') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'mitarbeiter_umwelt_id',$data,old('mitarbeiter_umwelt_id'),
                trans('documentForm.envWorkProtect'), trans('documentForm.selectEmployees') ) !!}
            </div>   
        </div><!--End input box-->
        
        