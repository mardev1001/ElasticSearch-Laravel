<div class="checkbox">
    <label>
        <input type="checkbox" 
        value="1" 
        name="{{$inputName}}" class=""
         @if( $required !=false ) required @endif
         @if( Request::is('*/edit') && isset( $data->$inputName ) && $data->$inputName == 1 )
    		 value="{{ $data->$inputName }}"
    	 @else
    	 	value="{{ $old }}"
    	 @endif
        >{{ ucfirst($label) }} @if( $required !=false ) {!! ViewHelper::asterisk() !!} @endif
    </label>
</div>
