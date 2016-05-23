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
                {!! ViewHelper::setUserSelect($mandantUsers,'approval_users[]',$data,old('approval_users[]'),
                trans('rightsRelease.release'), trans('rightsRelease.release'), true, array(), array(), array(), array('multiple') ) !!}
                   
                <div class="clearfix"></div>
                <div class="row">
                    <!-- input box-->
                    <div class="col-xs-6 col-md-3">
                        <div class="form-group">
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
                                <select name="roles[]" class="form-control select" data-placeholder="{{ trans('rightsRelease.roles') }}" multiple>
                                    <option value="Alle">Alle</option>
                                    @foreach($roles as $role)
                                    <option value="{{$role->id}}"
                                            {!! ViewHelper::setMultipleSelect($roles, $role->id, 'role_id') !!}
                                            >{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    @if( count($variants) > 0)
                        @foreach( $variants as $k=>$variant) 
                       
                        @foreach( $variant->documentMantant($data->id, $variant->id) as $v )
                         
                        @endforeach
                        
                        <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('rightsRelease.variante') }} {{$k+1}}</label>
                                    <select name="variante-{{$k+1}}[]" class="form-control select" 
                                     data-placeholder="{{ trans('rightsRelease.variante') }} {{$k+1}}" multiple>
                                        <option value="0"></option>
                                        <option value="Alle">Alle</option>
                                        
                                         @foreach( $mandants as $mandant)
                                            <option value="{{$mandant->id}}"
                                            {!! ViewHelper::setMultipleSelect($variant->documentMantant($data->id, $variant->id), $mandant->id, 'mandant_id') !!}
                                            >{{ $mandant->name }}</option>
                                        @endforeach
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
            @if( isset($backButton) )
                <a href="{{$backButton}}" class="btn btn-info"><span class="fa fa-chevron-left"></span> Zurück</a>
            @endif
            
            <button type="submit" class="btn btn-info" name="fast_publish">
                <span class="fa fa-exclamation-triangle"></span>  {{ trans('rightsRelease.fastPublish') }}
            </button>
            <button type="submit" class="btn btn-primary"  name="ask_publishers">
                <span class="fa fa-share"></span>  {{ trans('rightsRelease.share') }}
            </button>
            <button type="submit" class="btn btn-primary"  name="save">
                <span class="fa fa-floppy-o"></span>  {{ trans('rightsRelease.save') }}
            </button>
        </div>
        </form>
    @stop
    