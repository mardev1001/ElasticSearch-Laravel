@extends('master')
    @section('content')
        <h1 class="text-primary">
            {{ trans('controller.rightsRelease') }}
        </h1>
       
        {!! Form::open([
        'url' => 'dokumente/rechte-und-freigabe/'.$data->id,
        'method' => 'POST',
        'class' => 'horizontal-form' ]) !!}
            <div class="col-xs-12">
                    <div class="form-group">
                        <select name="fuuuu[]" class="form-control select" data-placeholder="{{ trans('rightsRelease.release') }}" multiple>
                            <option value="0"></option>
                            <option value="Frau Engel">Frau Engel</option>
                            <option value="Herr Floßmann">Herr Floßmann</option>
                            <option value="Herr Bittner">Herr Bittner</option>
                            <option value="Frau Sepp">Wiki</option>
                            <option value="Frau Hoffmann">Frau Hoffmann</option>
                        </select>
                    </div>
                <div class="clearfix"></div>
                <div class="row">
                    <!-- input box-->
                    <div class="col-xs-6 col-md-3">
                        <div class="form-group">
                            {!! ViewHelper::setInput('birthday', $data, old('released_to'),
                            trans('rightsRelease.releasedTo'), trans('rightsRelease.releasedTo'), false, 'text', ['datetimepicker']) !!}
                        </div>   
                    </div><!--End input box-->
                    
                    <!-- input box-->
                    <div class="col-xs-6 col-md-3">
                        <div class="form-group">
                            <br/>
                            {!! ViewHelper::setCheckbox('email',$data,old('email'),
                            trans('rightsRelease.sendEmail') ) !!}
                        </div>   
                    </div><!--End input box-->
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="col-xs-12">
                <h2 class="text-info">{{ trans('rightsRelease.right') }}</h2>
                <div class="row">
                    <div class="col-xs-12 col-md-6">
                           <div class="form-group">
                                <select name="" class="form-control select" data-placeholder="{{ trans('rightsRelease.roles') }}" multiple>
                                    <option value="0"></option>
                                    <option value="Frau">Alle</option>
                                    <option value="Herr">NLL</option>
                                    <option value="Herr">TA</option>
                                    <option value="Frau">GF</option>
                                    <option value="Herr">Verwaltung</option>
                                    <option value="Herr">Neptun MA</option>
                                    <option value="Frau">Neptun FK</option>
                                    <option value="Herr">Akq</option>
                                    <option value="Herr">DISPO</option>
                                </select>
                            </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    @if( count($variants) >0)
                        @foreach( $variants as $variant) 
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('rightsRelease.variante') }} 1</label>
                                    <select name="" class="form-control select" 
                                     data-placeholder="{{ trans('rightsRelease.variante') }} 1" multiple>
                                        <option value="0"></option>
                                        <option value="Frau">001</option>
                                        <option value="Herr">002</option>
                                        <option value="Herr">003</option>
                                        <option value="Frau">004</option>
                                        <option value="Herr">005</option>
                                        <option value="Herr">099</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="clearfix"></div>
                        @endforeach
                    @endif
                    
                </div>
            </div>
        <div class="clearfix"></div>
        <div class="col-md-6">
            <button class="btn btn-info"><span class="fa fa-exclamation-triangle"></span>  {{ trans('rightsRelease.fastPublish') }}</button>
            <button class="btn btn-primary"><span class="fa fa-share"></span>  {{ trans('rightsRelease.share') }}</button>
            <button type="submit" class="btn btn-primary simulate-"><span class="fa fa-floppy-o"></span>  {{ trans('rightsRelease.save') }}</button>
        </div>
        </form>
    @stop
    