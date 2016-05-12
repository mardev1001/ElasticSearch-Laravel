<div class="checkbox">
    <br/><!-- added break to vcenter with bootstrap input.form-control -->
    <label>
        <input type="checkbox" 
            value="1" 
            name="{{$inputName}}" 
            class="@foreach( $classes as $class) {{ $class }} @endforeach" 
            @foreach ($dataTags as $dataTag ) {{$dataTag}} @endforeach
             @if( $required !=false ) required @endif
             @if( Request::is('*/edit') && isset( $data->$inputName ) && $data->$inputName == 1 )
        		 value="{{ $data->$inputName }}"
        	 @else
        	 	value="{{ $old }}"
        	 @endif
            >{{ ucfirst($label) }} @if( $required !=false ) {!! ViewHelper::asterisk() !!} @endif
    </label>
</div>
