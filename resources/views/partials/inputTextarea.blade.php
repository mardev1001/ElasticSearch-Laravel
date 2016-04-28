<label class=" control-label">
    {{ ucfirst($label) }} @if( $required !=false ) {!! ViewHelper::asterisk() !!} @endif 
</label>
<textarea cols="30" rows="10" class="form-control"
name="{{$inputName}}" @if( $required !=false ) required @endif
placeholder="{{ ucfirst($label) }}">
 @if( Request::is('*/edit') && isset( $data->$inputName )  ) 
    {{ $data->$inputName }}
 @else
    {{ $old }}
 @endif
</textarea>
