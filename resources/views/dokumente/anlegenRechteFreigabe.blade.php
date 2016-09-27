@extends('master')

    @section('page-title')
        {{ trans('controller.create') }}
    @stop
    @section('content')
     
        {!! Form::open([
        'url' => 'dokumente/rechte-und-freigabe/'.$data->id,
        'method' => 'POST',
        'class' => 'horizontal-form freigabe-process' ]) !!}
            <div class="box-wrapper">
                @if( $data->name != null)   
                    <div class="row">
                       <div class="col-md-12"><h3 class="title doc-title">{{ $data->name }}</h3></div>
                    </div>
                @endif
                <h2 class="title">{{ trans('rightsRelease.release') }}</h2>
                <div class="box">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                            <label>{{ trans('rightsRelease.approver') }}*</label>
                            <select name="approval_users[]" class="form-control select approval-users" required data-placeholder="{{ trans('rightsRelease.approver') }}" multiple>
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
                                <div class="col-xs-12">
                                    <div class="form-group no-margin-bottom">
                                        <br>
                                        {!! ViewHelper::setCheckbox('email_approval',$data,old('email_approval'),
                                        trans('rightsRelease.sendEmail') ) !!}
                                    </div>   
                                </div><!--End input box--> 
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 box-wrapper">
                <h2 class="title">{{ trans('rightsRelease.right') }}</h2>
                <div class="box">
                    <div class="row">
                        <div class="col-xs-12 col-md-6">
                               <div class="form-group">
                                  <label>{{ trans('rightsRelease.roles') }}</label>
                                    <select name="roles[]" class="form-control select" data-placeholder="{{ trans('rightsRelease.roles') }}" multiple>
                                        @if( $data->approval_all_roles == true )
                                            <option value="0"></option>
                                            <option value="Alle" selected>Alle</option>
                                            @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{ $role->name }}</option>
                                            @endforeach
                                        @elseif(ViewHelper::countComplexMultipleSelect($data->editorVariant,'documentMandantRoles')  == false)
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
                                        <select name="variante-{{$k+1}}[]" class="form-control select freigabe-mandant" 
                                         data-placeholder="{{ trans('rightsRelease.variante') }} {{$k+1}}" 
                                         @if( count($variants) > 1)
                                            required
                                        @endif multiple>
                                            @if($variant->approval_all_mandants == true && count($variants) <= 1 )
                                                
                                                <option value="0"></option>
                                                <option value="Alle" selected>Alle</option>
                                                 @foreach( $mandants as $mandant)
                                                    <option value="{{$mandant->id}}">{{ $mandant->name }}</option>
                                                @endforeach
                                            @elseif( ViewHelper::countComplexMultipleSelect($variant,'documentMandantMandants',true) == false)
                                               
                                                <option value="0"></option>
                                                    @if( count($variants) <= 1)
                                                        <option value="Alle" selected>Alle</option>
                                                    @endif
                                                 @foreach( $mandants as $mandant)
                                                    <option value="{{$mandant->id}}">({{ $mandant->mandant_number }}) {{ $mandant->kurzname }}</option>
                                                @endforeach
                                            @else
                                                <option value="0"></option>
                                                 @if( count($variants) < 2 )
                                                    <option value="Alle">Alle</option>
                                                 @endif
                                                @foreach($mandants as $mandant)
                                                    <option value="{{$mandant->id}}"
                                                          {!! ViewHelper::setComplexMultipleSelect($variant,'documentMandantMandants', $mandant->id, 'mandant_id',true) !!}
                                                    >({{ $mandant->mandant_number }}) {{ $mandant->kurzname }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="clearfix"></div>
                            @endforeach
                        @endif
                      
                        <div class="col-md-12">
                            <br>
                            @if( isset($backButton) )
                                <a href="{{$backButton}}" class="btn btn-info no-margin-bottom"><span class="fa fa-chevron-left"></span> Zurück</a>
                            @endif
                            @if( Auth::user()->mandantRoles[0]->role_id == 1 || Auth::user()->mandantRoles[0]->role_id == 8)
                                <button type="submit" class="btn btn-info no-margin-bottom no-validate" name="fast_publish" value="fast_publish">
                                    <span class="fa fa-exclamation-triangle"></span>  {{ trans('rightsRelease.fastPublish') }}
                                </button>
                            @endif
                            <button type="submit" class="btn btn-primary no-margin-bottom validate"  name="ask_publishers" value="ask_publishers">
                                <span class="fa fa-share"></span>  {{ trans('rightsRelease.share') }}
                            </button>
                            <button type="submit" class="btn btn-primary no-margin-bottom no-validate"  name="save" value="save">
                                <span class="fa fa-floppy-o"></span>  {{ trans('rightsRelease.save') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <div class="clearfix"></div>
        
        </form>
    @stop
    
     @if( isset( $data->document_type_id ) )
           @section('preScript')
               <!-- variable for expanding document sidebar-->
               <script type="text/javascript">
                    var documentType = "{{ $data->documentType->name}}";
                   
                      
               </script>
               
               <!--patch for checking iso category document-->
                @if( isset($data->isoCategories->name) )
                    <script type="text/javascript">   
                        if( documentType == 'ISO Dokument')
                            var isoCategoryName = '{{ $data->isoCategories->name}}';
                    </script>
                @endif
               <!-- End variable for expanding document sidebar-->
           @stop
       @endif