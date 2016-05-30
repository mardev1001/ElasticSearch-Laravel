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
                <select name="approval_users[]" class="form-control select" data-placeholder="{{ trans('rightsRelease.release') }}" multiple>
                    <option value="0"></option>
                    @foreach($mandantUsers as $mandatUser)
                    <option value="{{$mandatUser->id}}"
                            {!! ViewHelper::setMultipleSelect($data->documentApprovals, $mandatUser->id, 'user_id') !!}
                            >{{ $mandatUser->first_name }} {{ $mandatUser->last_name }}</option>
                    @endforeach
                </select>
            
                <div class="clearfix"></div>
                <div class="row">
                    <!-- input box-->
                    <div class="col-xs-6 col-md-3">
                        <div class="form-group">
                            {!! ViewHelper::setCheckbox('email_approval',$data,old('email_approval'),
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
                                    @if($data->approval_all_roles == true)
                                        <option value="0"></option>
                                        <option value="Alle" selected>Alle</option>
                                        @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{ $role->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="0"></option>
                                        <option value="Alle">Alle</option>
                                        @foreach($roles as $role)
                                        <option value="{{$role->id}}"
                                                {!! ViewHelper::setComplexMultipleSelect($data->editorVariant,'documentMandantRoles', $role->id, 'role_id') !!}
                                                >{{ $role->name }}</option>
                                        @endforeach
                                    @endif
                                    
                                </select>
                            </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    @if( count($variants) > 0)
                        @foreach( $variants as $k=>$variant) 
                        <div class="col-xs-12 col-md-6">
                                <div class="form-group">
                                    <label>{{ trans('rightsRelease.variante') }} {{$k+1}}</label>
                                    
                                    <select name="variante-{{$k+1}}[]" class="form-control select" 
                                     data-placeholder="{{ trans('rightsRelease.variante') }} {{$k+1}}" multiple>
                                        @if($data->approval_all_roles == true)
                                            <option value="0"></option>
                                            <option value="Alle" selected>Alle</option>
                                             @foreach( $mandants as $mandant)
                                                <option value="{{$mandant->id}}">{{ $mandant->name }}</option>
                                            @endforeach
                                        @else
                                            <option value="0"></option>
                                            <option value="Alle">Alle</option>
                                            @foreach($mandants as $mandant)
                                                <option value="{{$mandant->id}}"
                                                     @foreach($data->editorVariant as $ev)
                                                      {!! ViewHelper::setComplexMultipleSelect($ev,'documentMandantMandants', $mandant->id, 'mandant_id',true) !!}
                                                     @endforeach
                                               >{{ $mandant->name }}</option>
                                            @endforeach
                                        @endif
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
            @if( Auth::user()->mandantRoles[0]->role_id == 1 || Auth::user()->mandantRoles[0]->role_id == 8)
                <button type="submit" class="btn btn-info" name="fast_publish">
                    <span class="fa fa-exclamation-triangle"></span>  {{ trans('rightsRelease.fastPublish') }}
                </button>
            @endif
            <button type="submit" class="btn btn-primary"  name="ask_publishers">
                <span class="fa fa-share"></span>  {{ trans('rightsRelease.share') }}
            </button>
            <button type="submit" class="btn btn-primary"  name="save">
                <span class="fa fa-floppy-o"></span>  {{ trans('rightsRelease.save') }}
            </button>
        </div>
        </form>
    @stop
    