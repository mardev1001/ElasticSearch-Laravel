 
    <label class="control-label">
        {{ ucfirst($label) }} @if( $required !=false ) {!! ViewHelper::asterisk() !!} @endif 
    </label>
    <input type="text" class="form-control" name="{{$inputName}}" 
    placeholder="{{ ucfirst($placeholder) }}"  autocomplete="off"
        @if( $required !=false ) 
            required 
        @endif
        
        @if( Request::is('*/edit') && isset( $data->$inputName ) && !empty($data->$inputName ) )
    		 value="{{ $data->$inputName }}"
    	 @else
    	 	value="{{ $old }}"
    	 @endif
    	/>