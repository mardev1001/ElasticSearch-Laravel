  <label class="control-label">
        {{ ucfirst($label) }} @if( $required !=false ) {!! ViewHelper::asterisk() !!} @endif 
    </label>
<select name="{{$inputName}}" class="form-control select @foreach($classes as $class) {{$class}} @endforeach"
data-placeholder="{{ ucfirst($placeholder) }}"
@foreach($dataTag as $tag) {{$tag}} @endforeach

@foreach($attributes as $attr) {{$attr}} @endforeach

    @if( $required !=false ) 
        required 
    @endif
    >
    <option></option>
    @if( count($collections) >0 ){
       @foreach($collections as $collection){
           <option value="{{$collection->id}}" 
                @if( !empty( $value) && $collection->id == $value)
                    selected
                @endif >
               {{$collection->name}}
           </option>
        @endforeach
    @endif
</select>