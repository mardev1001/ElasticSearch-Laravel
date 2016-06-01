{{-- DOKUMENT DETAILS --}}

@extends('master')

@section('content')


<div class="row">
        <div class="col-xs-12 col-md-12 white-bgrnd">
            <div class="fixed-row">
                <div class="fixed-position ">
                    <h1 class="page-title">
                        {{ $document->documentType->name }} @if($document->pdf_upload) PDF @endif 
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

<div class="row">
    <div class="col-lg-6">
        <h3>{{ $document->name }} ({{ trans('dokumentShow.version') }}: {{ $document->version }}, {{
            trans('dokumentShow.status') }}: {{ $document->documentStatus->name }})</h3>
    </div>
    <div class="col-lg-6">
        <div class="pull-right">
            <a href="{{route('dokumente.edit', $document->id)}}" class="btn btn-primary"><i class="fa fa-edit"></i> {{ trans('dokumentShow.edit')}} </a>
            <button class="btn btn-primary"><i class="fa fa-power-off"></i> {{ trans('dokumentShow.deactivate') }}</button>
            <button class="btn btn-primary"><i class="fa fa-files-o"></i> {{ trans('dokumentShow.new-version') }}</button>
            <button class="btn btn-primary"><i class="fa fa-history"></i> {{ trans('dokumentShow.history') }}</button>
            <button class="btn btn-primary"><i class="fa fa-star-o"></i> {{ trans('dokumentShow.favorite') }}</button>
        </div>
    </div>
</div>

<div class="clearfix"></div> <br>

<div class="row">
    <div class="col-xs-12">

        <div class="header">
            @if($document->documentAdressats)
            <h4>{{ trans('dokumentShow.adressat') }}: {{ $document->documentAdressats->name }}</h4>
            @endif
            <h4>{{ trans('dokumentShow.subject') }}: {{ $document->betreff }}</h4>
        </div>
        <br>
        <div class="content">
            <h4>{{ trans('dokumentShow.content') }}</h4>
            @foreach($document->editorVariant as $variant)
            <div class="variant-{{$variant->variant_number}}">
                <strong>{{ trans('dokumentShow.variant') }} {{$variant->variant_number}}</strong><br>
                {{ strip_tags($variant->inhalt) }}
            </div>
            <div class="clearfix"></div>
            <br>
            @endforeach
        </div>
        
        @if(count($document->documentUploads))
        <div class="attachments">
            @foreach($document->documentUploads as $attachment)
                <a target="_blank" href="#{{$attachment->file_path}}" class="">{{basename($attachment->file_path)}}</a><br>
            @endforeach
        </div>
        @endif
        
    </div>
</div>


<div class="clearfix"></div> <br>

<div class="row">
    <div class="col-xs-12">
        Kommentare kommen hier ...
    </div>
</div>

<div class="clearfix"></div> <br>

<div class="row">
    <div class="col-xs-12">
        <div class="pull-right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#kommentieren"><i class="fa fa-comment-o"></i> {{ trans('dokumentShow.commenting') }}</button>
            <button class="btn btn-primary"><i class="fa fa-check"></i> {{ trans('dokumentShow.approve') }}</button>
            <button class="btn btn-primary"><i class="fa fa-times"></i> {{ trans('dokumentShow.disapprove') }}</button>
        </div>
    </div>
</div>

<div class="clearfix"></div> <br>

<div id="kommentieren" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">{{ trans('dokumentShow.commenting') }}</h4>
            </div>

            <form action="">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">{{ trans('dokumentShow.subject') }}</label>
                        <input type="text" name="betreff" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">{{ trans('dokumentShow.comment') }}</label>
                        <textarea name="comment" cols="30" rows="5" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('dokumentShow.close') }}</button>
                    <button type="button" class="btn btn-primary">{{ trans('dokumentShow.save') }}</button>
                </div>
            </form>

        </div>
    </div>
</div>

@stop
