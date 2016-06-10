    <label class="control-label">
        {{ ucfirst($label) }}@if( $required !=false )* @endif 
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
    
    placeholder="{{ ucfirst($placeholder) }}@if( $required !=false )* @endif "  autocomplete="off"
        @if( $required !=false ) 
            required 
        @endif
        
        @if( isset( $data->$inputName ) && !empty($data->$inputName ) )
    		 value="{{ $data->$inputName }}"
    	 @else
    	 	value="{{ $old }}"
    	 @endif
    	/>