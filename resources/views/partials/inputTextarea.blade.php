<label class=" control-label">
    {{ ucfirst($label) }}@if( $required !=false )* @endif 
</label>
<textarea cols="30" rows="5" class="form-control" name="{{$inputName}}" @if( $required !=false ) required @endif  @if( $readonly !=false ) readonly @endif placeholder="{{ strtoupper($label) }}@if( $required !=false )* @endif">@if( Request::is('*/edit') && isset( $data->$inputName )  ) {{ $data->$inputName }} @else {{ $old }} @endif</textarea>
