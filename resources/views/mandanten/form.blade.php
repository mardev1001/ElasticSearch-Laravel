        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
               {!! ViewHelper::setInput('name',$data,old('name'),trans('mandantenForm.name') , 
               trans('mandantenForm.name') , true  ) !!}
            </div>   
        </div><!--End input box-->
           
        <!-- input box-->
        <div class="col-xs-3 col-lg-3">
            {!! ViewHelper::setCheckbox('hauptstelle',$data,old('hauptstelle'),trans('mandantenForm.hauptstelle') ) !!}
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
        <!-- input box-->
        <div class="col-lg-3 visible-yes"> <!-- add class hidden when activating js hidden -->
            <div class="form-group">
              {!! ViewHelper::setInput('mandant_number',$data,old('mandant_number'),trans('mandantenForm.mandantenNum') , 
               trans('mandantenForm.mandantenNum') , true  ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'mandant_id_hauptstelle',$data,old('mandant_id_hauptstelle'),
                trans('mandantenForm.mandanten') )  !!}
                
            </div>   
        </div><!--End input box-->
      
    <div class="clearfix"></div>



********* this will be wrapped in edit statement
    <h3>Zusatzdaten</h3>
  
        <!-- input box-->
        <div class="col-lg-3"> <!-- add class hidden when activating js hidden -->
            <div class="form-group">
                {!! ViewHelper::setInput('kurzname',$data,old('kurzname'),trans('mandantenForm.kurzname') ) !!}
            </div>   
        </div><!--End input box-->
        <!-- input box-->
        <div class="col-xs-6 col-lg-6">
            {!! ViewHelper::setCheckbox('rights_wiki',$data,old('rights_wiki'),trans('mandantenForm.rightsWiki') ) !!}
            
            {!! ViewHelper::setCheckbox('rights_admin',$data,old('rights_admin'),trans('mandantenForm.redaktion') ) !!}
        </div>
        
        <div class="clearfix"></div>
       
       <h3>Adresse</h3>
       
       <!-- input box-->
        <div class="col-lg-6">
            <div class="form-group">
                {!! ViewHelper::setInput('adresszusatz',$data,old('adresszusatz'),trans('mandantenForm.adresszusatz') ) !!}
              
            </div>   
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('strasse',$data,old('strasse'),trans('mandantenForm.strasse') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('hausnummer',$data,old('hausnummer'),trans('mandantenForm.hausnummer') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('plz',$data,old('plz'),trans('mandantenForm.plz') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('ort',$data,old('ort'),trans('mandantenForm.ort') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('telefon',$data,old('telefon'),trans('mandantenForm.telefon') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('kurzwahl',$data,old('kurzwahl'),trans('mandantenForm.kurzwahl') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('fax',$data,old('fax'),trans('mandantenForm.fax') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('email',$data,old('email'),trans('mandantenForm.email') ) !!}
            </div>   
        </div><!--End input box-->
        
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('website',$data,old('website'),trans('mandantenForm.website') ) !!}
            </div>   
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
        <h3>Geschäftsführer Infos</h3>
         
       
        
        <div class="row">  
           <div class="col-lg-8">
                <!-- input box-->
                <div class="col-lg-3">
                    <div class="form-group">
                          {!! ViewHelper::setSelect($collections,'asasasasasasa',$data,old('asasasasa'),
                        trans('mandantenForm.geschaftsfuhrer') )  !!}
                    </div>   
                </div><!--End input box-->
                <!-- input box-->
                <div class="col-lg-3">
                    <div class="form-group">
                        {!! ViewHelper::setInput('website',$data,old('website'),trans('mandantenForm.website') ) !!}
                    </div>   
                </div><!--End input box-->
                
                <!-- input box-->
                <div class="col-lg-3">
                    <button type="button" class="btn btn-primary">{{  trans('mandantenForm.historyAddButton')  }}</button>
                </div><!--End input box-->
                
                <div class="clearfix"></div>
                <br/>
                 <!-- input box-->
                <div class="col-lg-12">
                    <div class="form-group">
                        {!! ViewHelper::setArea('geschaftsfuhrer_history',$data,old('geschaftsfuhrer_history'),trans('mandantenForm.managerHistory') ) !!}
                    </div>   
                </div><!--End input box-->    
           </div>
           
           <!--logo box-->
           <div class="col-lg-4">
               <input type="file" id="logo" name="file" /> <br/>
               <img class="img-responsive" id="image-preview" src="http://placehold.it/550x320?text=placeholder"/>
               
           </div><!--end logo box-->
        </div>

        <div class="clearfix"></div>
        
        <h3>Zusätzliche Infos</h3>
         
       <!-- input box-->
        <div class="col-lg-6">
            <div class="form-group">
                {!! ViewHelper::setInput('prokura',$data,old('prokura'),trans('mandantenForm.procuration') ) !!}
            </div>   
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('betriebsnummmer',$data,old('betriebsnummmer'),trans('mandantenForm.operationNum') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('handelsregister',$data,old('handelsregister'),trans('mandantenForm.commercialRegister') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('handelsregister_sitz',$data,old('handelsregister_sitz'),trans('mandantenForm.hrSitz') ) !!}
            </div>   
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('mitarbeiter_lohn_id',$data,old('mitarbeiter_lohn_id'),trans('mandantenForm.taxNumber') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('ust_ident_number',$data,old('ust_ident_number'),trans('mandantenForm.userIdentNumber') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('zausatzinfo_steuer',$data,old('zausatzinfo_steuer'),trans('mandantenForm.infoControll') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('berufsgenossenschaft_number',$data,old('berufsgenossenschaft_number'),trans('mandantenForm.berufsgenossen') ) !!}
            </div>   
        </div><!--End input box-->

        <div class="clearfix"></div>
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('berufsgenossenschaft_zusatzinfo',$data,old('berufsgenossenschaft_zusatzinfo'),trans('mandantenForm.infosBg') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('erlaubniss_gultig_ab',$data,old('erlaubniss_gultig_ab'),trans('mandantenForm.permissions') ) !!}
            </div>   
        </div><!--End input box-->

        <div class="clearfix"></div>
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('erlaubniss_gultig_von',$data,old('erlaubniss_gultig_von'),trans('mandantenForm.permissionIssuer') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                {!! ViewHelper::setInput('heschaftsjahr',$data,old('heschaftsjahr'),trans('mandantenForm.fiscalYear') ) !!}
            </div>   
        </div><!--End input box-->

        <div class="clearfix"></div>
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('heschaftsjahr_info',$data,old('heschaftsjahr_info'),trans('mandantenForm.fiscalYear') ) !!}
            </div>   
        </div><!--End input box-->
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('bankverbindungen',$data,old('bankverbindungen'),trans('mandantenForm.bank') ) !!}
            </div>   
        </div><!--End input box-->
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                    {!! ViewHelper::setArea('info_wichtiges',$data,old('info_wichtiges'),trans('mandantenForm.importantInfo') ) !!}
            </div>   
        </div><!--End input box-->
        
          <!-- input box-->
        <div class="col-lg-12">
            <div class="form-group">
                {!! ViewHelper::setArea('info_sonstiges',$data,old('info_sonstiges'),trans('mandantenForm.other') ) !!}
            </div>   
        </div><!--End input box-->
        
       @include('partials.userRole')
        <div class="col-lg-3 bind-before">
            <div class="form-group">
                <button class="btn btn-primary" data-adder="userRole" action='generate-user-role'>Add new Role</button>
            </div>
        </div>
        
        
        <!-- input box-->
       <!-- <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'mitarbeiter_finanz_id',$data,old('mitarbeiter_finanz_id'),
                trans('mandantenForm.finantialAcc'), trans('mandantenForm.selectEmployees') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
       <!-- <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'mitarbeiter_edv_id',$data,old('mitarbeiter_edv_id'),
                trans('mandantenForm.fieldService'), trans('mandantenForm.selectEmployees') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
       <!-- <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'mitarbeiter_vertrieb_id',$data,old('mitarbeiter_vertrieb_id'),
                trans('mandantenForm.distribution'), trans('mandantenForm.selectEmployees') ) !!}
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
      <!--  <div class="col-lg-3"> 
            <div class="form-group">
                {!! ViewHelper::setSelect($collections,'mitarbeiter_umwelt_id',$data,old('mitarbeiter_umwelt_id'),
                trans('mandantenForm.envWorkProtect'), trans('mandantenForm.selectEmployees') ) !!}
            </div>   
        </div><!--End input box-->
        
        