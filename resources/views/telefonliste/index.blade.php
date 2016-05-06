{{-- TELEFONLISTE --}}

@extends('master')

@section('content')

<h1 class="text-primary">{{ trans('telefonListeForm.phone-list') }}</h1>


    <h4>{{ trans('telefonListeForm.overview') }}</h4>
    
    <div class="row">
        <div class="col-xs-12">
            
        </div>
    </div>

<div class="clearfix"></div> <br>
<!-- Modal link btn -->
<a href="#" class="btn btn-primary" 
   data-toggle="modal" 
   data-target="#basicModal">Modal link button</a><!--End modal link btn -->
   
   
   <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="fa fa-close"></span></button>
            <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <h3>Modal Body</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
        </div>
    </div>
  </div>
</div>
@stop
