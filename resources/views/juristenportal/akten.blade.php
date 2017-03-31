{{-- Akten index --}}

@extends('master')

@section('page-title') @lang('juristenPortal.aktenArt') @stop

@section('content')
<!-- add row-->
<div class="row">
    <div class="col-sm-12 ">
        <div class="box-wrapper">
            <h2 class="title"> @lang('juristenPortal.metaFieldsAddTitle')</h2>
            <div class="box  box-white">
                <div class="row">
                    {!! Form::open([ 'action' => 'JuristenPortalController@storeAkten','method'=>'POST']) !!} 
                      
                            <div class="col-md-6 col-lg-6">
                                {!! ViewHelper::setInput('name', '',old('name'), 
                                trans('inventoryList.name'), trans('inventoryList.name'), true) !!}
                            </div>
                        <div class="clearfix"></div><br/>
                        
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary">{{ strtolower(trans('isoKategorienForm.add') ) }}</button>
                        </div> 
                          
                    {!! Form::close() !!}
                </div>
            </div><!-- end box -->
        </div><!-- end box wrapper-->
    </div>
</div><!-- end dd-->
    
    @if(isset($akten) && count($akten))
    <fieldset class="form-group">
    
    <!--<h4 class="title">{{ trans('adressatenForm.adressats') }} {{ trans('adressatenForm.overview') }}</h4> <br>-->
     <div class="box-wrapper">    
        <div class="row">
            <div class="col-xs-12">
                <h4 class="title">@lang('juristenPortal.metaFieldsAddTitle')</h4>
                 <div class="box box-white">
                    @foreach($akten as $akt)
                    <div class="row">
                        {!! Form::open(['url' => ['beratungsportal/akten/'.$akt->id.'/update'], 'method' => 'patch']) !!}
                        <div class="col-xs-12 col-md-5 col-lg-5">
                             <input type="text" class="form-control" name="name" value="{{ $akt->name }}" placeholder="Name"/>
                        </div>
                        <div class="col-xs-12 col-md-5 col-lg-5">
                             select
                        </div>
                        <div class="col-xs-12 col-md-2 col-lg-5">
                            <button class="btn btn-primary" type="submit" name="save" value="1">{{ trans('adressatenForm.save') }}</button>
                            @if( !count($akt->metaInfos ) )
                                <a href="{{url('juristenportal/destroy-akten/'.$akt->id)}}" class="btn btn-xs btn-warning delete-prompt">
                                    entfernen
                                </a><br>
                            @endif
                        </div>
                        {!! Form::close() !!}
                        <div class="clearfix"></div>
                        <br/>
                        
                        
                        
                       
                            
                    </div><!--end .row (category row) -->
                    <hr/><br>
                    @endforeach
                    
                    
                   
                    
                </div>
            </div>
        </div>
    </div>
    </fieldset>
    @endif

@stop
