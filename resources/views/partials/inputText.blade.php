
    <label class="control-label">
        {{ ($label) }}@if( $required !=false && $label != '' )* @endif 
    </label> 
    <input type="{{ $type }}" class="form-control 
    @foreach( $classes as $class)
        {{ $class }}
    @endforeach
    " 
    name="{{ $inputName }}"

    @foreach ($dataTags as $dataTag ) 
        {{$dataTag}}
    @endforeach
    
    placeholder="{{ $placeholder }}@if( $required !=false )* @endif "  autocomplete="off"
        @if( $required !=false ) 
            required 
        @endif
         
        @if( isset( $data->$inputName ) && !empty($data->$inputName ) && $data->$inputName != NULL )
    		 value="{{ $data->$inputName }}"
    	 @else
    	 	value="{{ $old }}"
    	 @endif
    	/>