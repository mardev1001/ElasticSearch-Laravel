<h3>Dokumente anlegen - Daten Eingabe - Dokumentenart - Upload</h3>

<input type="hidden" name="model_id" value="{{$data->id}}" />
<br/>
<div class="row">
    <!-- input box-->
    <div class="col-lg-6"> 
        <div class="form-group">
            <label>Dokumentenupload</label>
            <input type="file" name="file[]" class="form-control" multiple 
            @if( $data->documentUploads()->count() < 1 )
                required
            @endif />
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
    
</div>

