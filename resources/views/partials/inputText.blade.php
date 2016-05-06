    <label class="control-label">
        {{ ucfirst($label) }} @if( $required !=false ) {!! ViewHelper::asterisk() !!} @endif 
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