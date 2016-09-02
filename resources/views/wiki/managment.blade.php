{{-- TELEFONLISTE --}}

@extends('master')

@section('page-title') {{ trans('wiki.managment') }} - @if( isset($admin) && $admin == true) Administrator @else User @endif @stop

@section('content')
<div class="row">
    <div class="col-xs-12 box-wrapper">
        <div class="box">
            <div class="row">
                {!! Form::open(['action' => 'WikiController@searchManagment', 'method'=>'POST']) !!}
                     @if( isset($admin) && $admin == true) 
                         <input type="hidden" name="admin" value="1" />
                     @else 
                         <input type="hidden" name="admin" value="0" />
                     @endif
                    <div class="wiki-managment-search">
                        <div class="col-md-3 mb-20">
                            {!! ViewHelper::setInput('name', $data,old('name'), trans('wiki.name') ) !!}
                        </div>
                        <div class="col-md-3 mb-20">
                            {!! ViewHelper::setInput('subject',  $data,old('name'), trans('wiki.subject') ) !!}
                        </div>
                      
                        <div class="col-md-3 mb-20">
                            {!! ViewHelper::setInput('date_from',  $data,old('date_from'), trans('wiki.dateFrom'), trans('wiki.dateFrom') 
                            , false, 'text' , ['datetimepicker']  ) !!}
                        </div>
                        <div class="col-md-3 mb-20">
                            {!! ViewHelper::setInput('date_to', $data,old('date_to'), trans('wiki.dateTo'), trans('wiki.dateTo'), 
                            false, 'text' , ['datetimepicker']  ) !!}
                            
                        </div>
                        <div class="col-md-3 mb-20">
                             {!! ViewHelper::setSelect($categories,'category',$data,old('category'), trans('wiki.category') ) !!}
                            
                        </div>
                       
                        <div class="col-md-3 mb-20">
                             {!! ViewHelper::setSelect($statuses,'status',$data,old('status'),trans('wiki.status') ) !!}
                            
                        </div>
                        @if( isset($admin) && $admin == true) 
                            <div class="col-md-3 mb-20">
                                {!! ViewHelper::setUserSelect($wikiUsers,'ersteller',$data,old('ersteller'),trans('wiki.user') ) !!}
                            </div>
                        @endif
                        <div class="col-md-3 col-lg-3 mb-20">
                            <span class="custom-input-group-btn">
                                <button type="submit" class="btn btn-primary no-margin-bottom">
                                    {{ trans('navigation.search') }} 
                                </button>
                            </span>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div><!-- end box -->
    </div><!-- end box wrapper-->
    
    <!-- top categorie box-->
    <div class="col-xs-12 box-wrapper">
        <div class="box">
             <table class="table data-table">
                <thead>
                    <th  class="text-center valign">Name</th>
                    <th  class="text-center valign">Kategorie</th>
                    <th class="text-center valign">Status</th>
                    <th class="text-center valign no-sort">Ersteller</th>
                    <th class="text-center valign no-sort">Datum</th>
                    <th class="text-center valign no-sort">Optionen</th>
                </thead>
                <tbody>
                    @if(count($wikies) > 0)
                        @foreach($wikies as $k => $data)
                        <tr>
                                
                                <td class="text-center valign">{{ $data->name }} </td>
                                <td class="text-center valign ">
                                    {{ $data->category->name }}
                                </td>
                                <td class="text-center valign ">
                                    {{ $data->status->name }}
                                </td>
                                <td class="text-center valign"> 
                                    {{ $data->user->first_name }} {{ $data->user->last_name }}
                                </td>
                                <td class="text-center valign"> 
                                    {{ $data->created_at }}
                                </td>
                                <td class="valign table-options text-center">
                                    <a href="/wiki/{{$data->id}}/edit" class="btn btn-xs btn-primary">Bearbeiten</a><br>
                                </td>
                            </tr>
                            
                        @endforeach
                    @else
                        <tr><td colspan="6" class="text-center"> Keine Daten vorhanden. </td></tr>
                    @endif
                
                </tbody>
            </table>
        </div><!-- end box -->
         
    </div><!--end  top categorie box wrapper-->

 
    
</div><!-- end main row-->




@stop
