        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                <label class=" control-label">{{ucfirst( trans('navigation.mandant') )}} {{trans('forms.name')}} {!! ViewHelper::asterisk() !!}</label>
                <input type="text" class="form-control" name="name" 
                placeholder="{{ucfirst( trans('navigation.mandant') )}} {{trans('forms.name')}}" required autocomplete="off"
        		 @if( Request::is('*/edit') && $data->name )
        			 value="{{ $data->name }}"
        		 @else
        		 	value="{{ old('name') }}"
        		 @endif
        		/>
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-xs-3 col-lg-3">
            <div class="checkbox">
              <label><input type="checkbox" data-hideswitch data-yes="visible-yes" data-no="visible-no" value="{{trans('forms.haupstelle')}}" name="">{{trans('forms.haupstelle')}}</label>
            </div>
            
        </div><!--End input box-->
        
        <div class="clearfix"></div>
        
        <!-- input box-->
        <div class="col-lg-3 visible-yes"> <!-- add class hidden when activating js hidden -->
            <div class="form-group">
                <label class=" control-label">{{ ucfirst( trans('forms.mandantenNum') ) }} {{ ViewHelper::asterisk() }}</label>
                <input type="text" class="form-control" name="name" placeholder="{{ucfirst( trans('forms.mandantenNum') )}}" required  autocomplete="off"
        		 @if( Request::is('*/edit') && $data->name )
        			 value="{{ $data->name }}"
        		 @else
        		 	value="{{ old('name') }}"
        		 @endif
        		/>
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3"> 
            <div class="form-group">
                <label class=" control-label">{{ucfirst(trans('forms.anschrift') )}} <i class="fa fa-asterisk text-info"></i></label>
                <select class="form-control select" data-placeholder="{{ucfirst(trans('forms.anschrift') )}}">
                     {!! ViewHelper::setSelect($documentStatus) !!}
                </select>
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-lg-3 visible-no"> <!-- add class hidden when activating js hidden -->
            <div class="form-group">
                <label class=" control-label">{{ucfirst(trans('forms.anschrift') )}} </label>
                <input type="text" class="form-control" name="name" placeholder="{{ucfirst( trans('forms.anschrift') )}}" autocomplete="off"  
        		 @if( Request::is('*/edit') && $data->name )
        			 value="{{ $data->name }}"
        		 @else
        		 	value="{{ old('name') }}"
        		 @endif
        		/>
            </div>   
        </div><!--End input box-->

    <div class="clearfix"></div>

    @if( Request::is('*/edit') )
    <h3>Zusatzdaten</h3>
        <!-- input box-->
        <div class="col-lg-3"> <!-- add class hidden when activating js hidden -->
            <div class="form-group">
                <label class=" control-label">{{ ucfirst( trans('forms.kurzname') ) }}</label>
                <input type="text" class="form-control" name="name" placeholder="{{ucfirst( trans('forms.kurzname') )}}"  autocomplete="off"
        		 @if( Request::is('*/edit') && $data->name )
        			 value="{{ $data->name }}"
        		 @else
        		 	value="{{ old('name') }}"
        		 @endif
        		/>
            </div>   
        </div><!--End input box-->
        
        <!-- input box-->
        <div class="col-xs-6 col-lg-6">
            <div class="checkbox">
              <label><input type="checkbox" value="" name="">Wiki rechte</label>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" value="" name="">{{ trans('navigation.redaktion') }}</label>
            </div>
        </div>
        
        <div class="clearfix"></div>
       
       <h3>Adresse</h3>
       
       <!-- input box-->
        <div class="col-lg-3">
            <div class="form-group">
                <label class=" control-label">{{ ucfirst( trans('forms.kurzname') ) }}</label>
                <input type="text" class="form-control" name="name" placeholder="{{ucfirst( trans('forms.kurzname') )}}"  autocomplete="off"
        		 @if( Request::is('*/edit') && $data->name )
        			 value="{{ $data->name }}"
        		 @else
        		 	value="{{ old('name') }}"
        		 @endif
        		/>
            </div>   
        </div><!--End input box-->
        
        
    @endif


    