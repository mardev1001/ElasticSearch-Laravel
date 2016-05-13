<h3>Dokumente anlegen - Daten Eingabe - Dokumentenart - Editor</h3>
<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setCheckbox('show_name',$data,old('show_name'),trans('documentForm.showName') ) !!}
    </div>   
</div><!--End input box-->

<div class="clearfix"></div>

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setSelect($adressats,'adressat_id',$data,old('do'),
                trans('documentForm.documentType'), trans('documentForm.documentType') ) !!}
    </div>   
</div><!--End input box-->

<!-- input box-->
<div class="col-lg-3"> 
    <div class="form-group">
        {!! ViewHelper::setInput('betreff',$data,old('betreff'),trans('documentForm.subject') , 
               trans('documentForm.subject') , true  ) !!}
    </div>   
</div><!--End input box-->
<div class="clearfix"></div>
    <div class="parent-tabs col-xs-12 col-md-12">
    <hr/>
      <!-- Tab panes -->
    <a href="#" class="btn btn-primary add-tab"><span class="fa fa-plus"></span> Neue Variante</a>
    <br><br>
    <ul class="nav nav-tabs" id="tabs">
       
    </ul>
    
    <div class="tab-content">
        
    </div>
  </div>

<div class="clearfix"></div>