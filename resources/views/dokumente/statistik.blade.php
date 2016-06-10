{{-- STATISTIK --}}

@extends('master')

@section('page-title')
    {{ trans('statistikForm.stats') }} - Dokument QMR 123 - V2
@stop

@section('content')

<div class="row">
    <div class="col-xs-12">
        
        <h4>{{ trans('statistikForm.overview') }}</h4>
        
        <div class="panel-group" role="tablist" data-multiselectable="true" aria-multiselectable="true">
        
        @for($i = 1; $i < 5; $i++)
            <div id="panel-{{$i}}" class="panel panel-primary">
                
                <div class="panel-heading" role="tab" id="heading-{{$i}}">
                    <h4 class="panel-title">
                        <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-{{$i}}" aria-expanded="false" aria-controls="collapse-{{$i}}">
                            {{ trans('statistikForm.mandant') }} #{{$i}} [4/8]
                        </a>
                    </h4>
                </div>
                
                <div id="collapse-{{$i}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{$i}}">
                    <table class="table">
                        <tr>
                            <th>{{ trans('statistikForm.user') }}</th>
                            <th>{{ trans('statistikForm.read') }}</th>
                            <th>{{ trans('statistikForm.date') }} {{ trans('statistikForm.first') }}</th>
                            <th>{{ trans('statistikForm.date') }} {{ trans('statistikForm.last') }}</th>
                        </tr>
                        <tr>
                            <td>Max Mustermann</td>
                            <td>Ja</td>
                            <td>{{ date('H:m:s d.m.Y') }}</td>
                            <td>{{ date('H:m:s d.m.Y') }}</td>
                        </tr>
                    </table>
                </div>
                
            </div>
        @endfor
        
        </div>
        
    </div>
</div>

@stop
