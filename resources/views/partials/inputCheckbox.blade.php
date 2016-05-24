<div class="checkbox">
    <label>
        <input type="checkbox" 
            value="1" 
            name="{{$inputName}}"
            class="@foreach( $classes as $class) {{ $class }} @endforeach" 
            @foreach ($dataTags as $dataTag ) {{$dataTag}} @endforeach
             @if( $required !=false ) required @endif
             @if( isset( $data->$inputName ) && ( $data->$inputName == 1  ) )
        		checked
        	 @elseif( $old == 1 )
        	     checked
        	 @endif
            >{{ ucfirst($label) }} @if( $required !=false ) {!! ViewHelper::asterisk() !!} @endif
    </label>
</div>
