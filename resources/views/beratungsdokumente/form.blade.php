@extends('master')

@section('page-title')
  Dokumente bearbeiten / anlegen - Grundinfos
@stop


@section('content')

            <!-- input box-->
            <div class="col-md-4 col-lg-4"> 
                <div class="form-group">
                        {!! ViewHelper::setInput('date_published',$data,old('date_published'),trans('beratungsDokument.datum')) !!}
                    
                </div>   
            </div><!--End input box-->



@stop