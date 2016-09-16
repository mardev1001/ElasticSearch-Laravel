{{-- Wiki details --}}

@extends('master')

@section('page-title') Wiki - Übersicht @stop

@section('content')

    <div class="box-wrapper ">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                    <h3 class="title">
                    {{ $data->name }}
                    <br>
                    <span class="text">
                       <strong>
                           ( {{ trans('dokumentShow.status') }}: {{ $data->status->name }}, {{$data->created_at}}, {{ $data->user->first_name.' '.$data->user->last_name }})
                       </strong> 
                    </span>
                </h3>
            </div>
        </div>
        <div class="box">
            <div class="row">
                <div class="col-sm-8 col-md-9 col-lg-10">

                    <div class="clearfix"></div>

                    <div class="row">
                        <div class="col-xs-12">

                            <!--<div class="header">-->
                            <!--    <p class="text-small">{{ $data->created_at }}</p> -->
                            <!--    @if($data->documentAdressats)-->
                            <!--    <p><b>{{ trans('dokumentShow.adressat') }}:</b> {{ $data->documentAdressats->name }}</p> -->
                            <!--    @endif-->
                        <!--     @if( !empty( $data->betreff ))-->
                            <!--        <p><b>{{ trans('dokumentShow.subject') }}:</b> {{ $data->betreff }}</p> -->
                            <!--     @endif-->
                        <!--</div>-->

                            <div class="content">
                                <!--<p class="text-strong title-small">{{ trans('dokumentShow.content') }}</p>-->

                                @if($data->content)
                                    <div>
                                        {!! ViewHelper::stripTags($data->content, array('div' ) ) !!}
                                    </div>
                                @endif

                            </div><!-- end .content -->

                            <div class="clearfix"></div> <br>

                            

                           

                        </div><!--end col-xs-12-->
                    </div><!--end row-->

                    <div class="clearfix"></div> <br>
                

                </div><!-- end .col-sm-8 .col-md-9 .col-lg-10 -->
                

                <div class="col-sm-4 col-md-3 col-lg-2 btns">
                   <a href="{{route('wiki.edit', $data->id)}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.edit')}} </a>
                  

                    <a href="/wiki/{{$data->id}}/activate" class="btn btn-primary pull-right">
                        @if( $data->active  == false)
                            {{ trans('dokumentShow.activate') }}
                        @else
                            {{ trans('dokumentShow.deactivate') }}
                        @endif</a>
                    <a href="/wiki/duplicate/{{$data->id}}" class="btn btn-primary pull-right">{{ trans('dokumentShow.duplicate') }}</a>
                </div><!--end col-sm-4 col-md-3 col-lg-2 btns-->
              
            </div>
          
        </div>
    </div>
    @stop
