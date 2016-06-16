@section('page-title') {{ trans('controller.create') }} @stop
<h3 class="title">{{ trans('controller.editor') }}</h3>

<div class="row">  
    
    <!-- input box-->
    <div class="col-lg-5"> 
        <div class="form-group">
            {!! ViewHelper::setSelect($adressats,'adressat_id',$data,old('adressat_id'),
                    trans('documentForm.adressat'), trans('documentForm.adressat') ) !!}
        </div>   
    </div><!--End input box-->
    
    <!-- input box-->
    <div class="col-lg-5"> 
        <div class="form-group">
            {!! ViewHelper::setInput('betreff',$data,old('betreff'),trans('documentForm.subject') , 
                   trans('documentForm.subject') , false  ) !!}
        </div>   
    </div><!--End input box-->
    
    <div class="clearfix"></div>
    <!-- input box-->
    <div class="col-lg-3"> 
        <div class="form-group">
            {!! ViewHelper::setCheckbox('show_name',$data,old('show_name'),trans('documentForm.showName')) !!}
        </div>   
    </div><!--End input box-->
    
      
    
</div>
    
<div class="clearfix"></div>

<div class="row">
    <div class="col-xs-12">
        <div class="pull-right">
            <a href="#" class="btn btn-primary">Seiten Vorschau</a>
            <a href="#" class="btn btn-primary">PDF Vorschau</a>
        </div>
    </div>
</div>


<input type="hidden" name="model_id" value="{{$data->id}}" />
<div class="col-xs-12 editable" data-id='inhalt'>
    @foreach($data->editorVariant as $variant)
        {!! $variant->inhalt  !!}
    @endforeach
</div>
<div class="clearfix"></div>
<br/>
<div class="row">
    <!-- input box-->
    <div class="col-lg-6"> 
        <div class="form-group">
            <input type="file" name="files[]" class="form-control"  
            @if( $data->documentUploads()->count() < 1 )
                required
            @endif
            />
        </div>   
    </div><!--End input box-->
    @if( $data->documentUploads()->count() > 0 )
        <div class="col-lg-6 "> 
        <span class="lead"> Hochgeladene Dateien</span>
        @foreach($data->documentUploads as $doc)
           <p class="text-info"><span class="fa fa-file-o"></span> {{ $doc->file_path }}</p>
        @endforeach
      
        </div><!--End input box-->
    @endif
</div><br>