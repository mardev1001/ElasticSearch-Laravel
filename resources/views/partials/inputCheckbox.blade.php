<div class="checkbox">
    
        <input type="checkbox" 
            value="1" 
            name="{{$inputName}}"
            id="{{$inputName}}"
            class="@foreach( $classes as $class) {{ $class }} @endforeach" 
            @foreach ($dataTags as $dataTag ) {{$dataTag}} @endforeach
             @if( $required !=false ) required @endif
             @if( isset( $data->$inputName ) && ( $data->$inputName == 1  ) )
        		checked
        	 @elseif( $old == 1 )
        	     checked
        	 @endif
            >
    <label for="{{$inputName}}">
        {{ ucfirst($label) }} @if( $required !=false ) {!! ViewHelper::asterisk() !!} @endif
    </label>
</div>
