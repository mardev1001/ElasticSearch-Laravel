{{-- STATISTIK --}}

@extends('master')

@section('page-title')
    {{ trans('statistikForm.stats') }} - {{$document->name}}
@stop

@section('content')

<div class="row">
    <div class="col-xs-12">
        
        <!--<h4>{{ trans('statistikForm.overview') }}</h4>-->
        
        <div class="panel-group" role="tablist" data-multiselectable="true" aria-multiselectable="true">
        
        @foreach($mandants as $mandant)
            <div id="panel-{{$mandant->id}}" class="panel panel-primary">
                
                <div class="panel-heading" role="tab" id="heading-{{$mandant->id}}">
                    <h4 class="panel-title">
                        <a class="collapsed transform-normal" role="button" data-toggle="collapse" href="#collapse-{{$mandant->id}}" aria-expanded="false" aria-controls="collapse-{{$mandant->id}}">
                            ({{ $mandant->mandant_number }}) {{$mandant->kurzname}} [{{count($documentReadersCount[$mandant->id])}}/{{count(ViewHelper::getActiveUsers($mandant))}}]
                        </a>
                    </h4>
                </div>
                
                <div id="collapse-{{$mandant->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{$mandant->id}}">
                    <div class="box">
                        <table class="table data-table">
                                <tr>
                                    <th>{{ trans('statistikForm.user') }}</th>
                                    <th>{{ trans('statistikForm.read') }}</th>
                                    <th>{{ trans('statistikForm.date') }} {{ trans('statistikForm.first') }}</th>
                                    <th>{{ trans('statistikForm.date') }} {{ trans('statistikForm.last') }}</th>
                                </tr>
                            @foreach( $mandant->mandantUsers as $mandantUser )
                                
                                
                                @if($mandantUser->user->active)
                                    @if(isset($documentReaders[$mandantUser->user_id]))
                                        @if(\Carbon\Carbon::parse($documentReaders[$mandantUser->user_id]['date_read_last'])->eq(\Carbon\Carbon::parse('0000-00-00 00:00:00')))
                                            <tr>
                                                <td>{{$mandantUser->user->first_name}} {{$mandantUser->user->last_name}}</td>
                                                <td>Neu</td>
                                                <td>-</td>
                                                <td>-</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>{{$mandantUser->user->first_name}} {{$mandantUser->user->last_name}}</td>
                                                <td>Ja</td>
                                                <td>{{\Carbon\Carbon::parse($documentReaders[$mandantUser->user_id]['date_read'])->format('d.m.Y H:i:s')}}</td>
                                                <td>{{\Carbon\Carbon::parse($documentReaders[$mandantUser->user_id]['date_read_last'])->format('d.m.Y H:i:s')}}</td>
                                            </tr>
                                        @endif
                                    @else
                                        <tr>
                                            <td>{{$mandantUser->user->first_name}} {{$mandantUser->user->last_name}}</td>
                                            <td>Nein</td>
                                            <td>-</td>
                                            <td>-</td>
                                        </tr>
                                    @endif
                                @endif
                                
                            @endforeach
                        </table>
                    </div>
                </div>
                
            </div>
        @endforeach
        
        </div>
        
    </div>
</div>

@stop
