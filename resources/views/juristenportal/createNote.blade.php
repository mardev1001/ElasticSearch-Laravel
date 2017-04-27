@extends('master')

@section('page-title') {{ 'Notiz anlegen' }} @stop

@section('content')


<div class="col-md-12 box-wrapper"> 
    <div class="box">
        <div class="row">
            
             {!! Form::open(['route' => 'notice.store', 'method' => 'POST', 'class' => 'horizontal-form' ]) !!} 
            
            <!-- row 1-->
            <div class="col-md-4 col-lg-3 "> 
                <div class="form-group">
                    <label class="control-label">Mandant</label>
                    {!! ViewHelper::setUserSelect($mandantUsers,'mandant', '', old('mandant'),'', 'Mandant',false ) !!}
                </div>
            </div>
            
            
            <div class="col-md-4 col-lg-3"> 
                <div class="form-group">
                    <label class="control-label"> Mitarbeiter </label>
                    <select name="user" id="user" class="form-control select empty-select" data-placeholder="Mitarbeiter">
                        <option value="" data-position="" select >&nbsp;</option>
                        @foreach($mitarbeiterUsers as $mitarbeiterUser)
                            <option value="{{$mitarbeiterUser->id}}" data-position="{{$mitarbeiterUser->position}}">
                                {{ $mitarbeiterUser->last_name }} {{ $mitarbeiterUser->first_name }}  
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    {!! ViewHelper::setInput('username', '', old('username'), 'Mitarbeiter Name', 'Mitarbeiter Name', false, '', array(''), array('id=username')) !!}
                </div>
            </div>
            
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                   {!! ViewHelper::setInput('date', '', old('date'), 'Datum', 'Datum', false, 'text', ['datetimepicker']) !!}
                </div>
            </div>
            
            
            <!-- row 2-->
            <div class="col-md-4 col-lg-3"> 
                <div class="form-group">
                    <div class="checkbox text-right">
                        {!! ViewHelper::setCheckbox('recall', '', old('recall'), trans('wünscht Rückruf')) !!}
                    </div>
                </div>   
            </div>

            <div class="col-md-4 col-lg-3"> 
                <div class="form-group ">
                      {!! ViewHelper::setInput('phone', '', old('phone'), 'Telefonnummer', 'Telefonnummer', false) !!}
                </div>   
            </div>
            
            <div class="col-md-4 col-lg-3"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('function', '', old('function'), 'Function', 'Function', false, '', array(''), array('id=function')) !!}
                </div>   
            </div><!--End input box-->
            
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                   {!! ViewHelper::setInput('time', '', old('time'), 'Uhrzeit', 'Uhrzeit', false, 'text', ['timepicker']) !!}
                </div>
            </div>
            
            <!-- row 3-->
          
            <div class="col-md-3 col-lg-3"> 
                <div class="form-group">
                    
                </div>   
            </div>
          
            <div class="col-md-9 col-lg-9"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('message', '', old('username'), 'Nachricht für / Besprechen mit', 'Nachricht für / Besprechen mit', false) !!}
                </div>   
            </div>
            
            <!-- text editor-->
            <div class="clearfix"></div>
          
            <div class="col-xs-10"> 
                <div class="form-group">
                    {!! ViewHelper::setInput('betreff', '', old('betreff'), 'Betreff', 'Betreff', false) !!}
                </div>   
            </div>
            
            <div class="col-xs-2"> 
                <div class="form-group">
                    
                </div>   
            </div>
            
            <div class="col-xs-10">
                <div class="variant" data-id='content'>
                    @if( isset($data->content) )
                        {!! $data->content !!}
                    @endif
                </div>
                
            </div>
            
            <div class="col-xs-2 text-center">
                <div class="form-group">
                    <a href="{{ url('#') }}" class="btn btn-primary no-margin-bottom">drucken</a>
                </div>
                <div class="form-group">
                    <a href="{{ url('beratungsportal/calendar') }}" class="btn btn-primary no-margin-bottom">Wiedervorlage</a>
                </div>
                <div class="form-group form-buttons">
                    <a href="{{ url('#') }}" class="btn btn-primary no-margin-bottom">zu Akte hinzufügen</a>
                </div>
                <div class="form-group form-buttons">
                    <a href="{{ url('#') }}" class="btn btn-primary no-margin-bottom">neue Akte anlegen</a>
                </div>
                <div class="form-group form-buttons">
                    <a href="{{ url('#') }}" class="btn btn-primary no-margin-bottom">Notiz archivieren</a>
                </div>
            </div>
            
        </div>

    </div>
    
    <br/>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-xs-12 form-buttons">
            {{ Form::submit('speichern und weiter', array('class' => 'btn btn-primary no-margin-bottom')) }}
        </div>
    </div>
    <div class="clearfix"></div> 
@stop

@section('script')
<script>
$('#user').change(function(event) {
    var selected = $(this).find('option:selected');
    var name = $.trim(selected.text());
    var position = selected.data('position');
    $('#username').val(name);
    $('#function').val(position);
});
</script>



@stop