@extends('master')
    @section('page-title'){{ trans('juristenPortal.upload') }} @stop
    @section('content')
        <div class="col-xs-12 box-wrapper">
            <h3 class="title">{{ trans('juristenPortal.upload') }}</h3>
            <div class="box">
               {!! Form::open([
                   'url' => route('juristenportal.upload'),
                   'method' => 'POST',
                   'enctype' => 'multipart/form-data',
                   'class' => 'horizontal-form'
                   ]) 
               !!}
                    <div class="row">
                        <!-- input box-->
                        <div class="col-lg-6"> 
                            <div class="form-group">
                                
                                <input type="file" name="file[]" class="form-control" multiple required />
                            </div>   
                        </div><!--End input box-->
                        
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary">{{ strtolower(trans('juristenPortal.upload') ) }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    @endsection


