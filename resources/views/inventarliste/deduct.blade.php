{{-- Invetarliste Abrechnen --}}

@extends('master')

@section('page-title') {{ trans('navigation.inventoryAbrechnen') }} @stop

@section('content')


<!--search row-->
<div class="row">
    <div class="col-sm-12 ">
        <div class="box-wrapper">
            <h2 class="title"> @lang('inventoryList.searchDeduct')</h2>
            <div class="box  box-white">
                <div class="row">
                    {!! Form::open(['action' => 'InventoryController@searchAbrechnen', 'method'=>'POST']) !!}
                        <div class="input-group">
                            <div class="col-md-12 col-lg-12">
                                @if( isset($searchInput) ) 
                                    {!! ViewHelper::setInput('search', '',$searchInput, 
                                    trans('inventoryList.name'), trans('inventoryList.name'), true) !!}
                                @else
                                    {!! ViewHelper::setInput('search', '',old('search'), trans('inventoryList.name'),
                                    trans('inventoryList.name'), true) !!}
                                @endif
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <span class="custom-input-group-btn">
                                    <button type="submit" class="btn btn-primary no-margin-bottom">
                                        {{ trans('navigation.search') }} 
                                    </button>
                                    <button type="submit" class="btn btn-primary no-margin-bottom">
                                        {{ trans('inventoryList.open') }} 
                                    </button>
                                    <button type="submit" class="btn btn-primary no-margin-bottom">
                                        {{ trans('inventoryList.billed') }} 
                                    </button>
                                </span>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div><!-- end box -->
        </div><!-- end box wrapper-->
    </div>
</div><!-- end search row -->

    @if( $searchCategories || $searchInventory )
        <!-- search results categories categorie box-->
        @if( count($searchCategories) )
            <h2 class="title">{{ trans('inventoryList.categorySearchResults') }}</h2>
                @foreach( $searchCategories as $category)
                    <div class="panel-group">
                        <div class="panel panel-primary" id="panelInventory{{$category->id}}">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                        <a data-toggle="collapse" data-target="#collapseInventory{{$category->id}}" class="collapsed transform-normal" 
                                           href="#collapseInventory{{$category->id}}">
                                          {{$category->name}} ({{ $category->items->count() }})
                                        </a>
                                </h4>
                            </div><!--end .panel-heading -->    
                        </div><!--end .panel.panel-primary -->
                    
                        <div id="collapseInventory{{$category->id}}" class="panel-collapse collapse">
                            <div class="panel-body box-white">
                                <table class="table data-table box-white">
                                <thead>
                                    <th  class="text-center valign">@lang('inventoryList.name')</th>
                                    <th  class="text-center valign">@lang('inventoryList.number')</th>
                                    <th class="text-center valign">@lang('inventoryList.size')</th>
                                    <th class="text-center valign">@lang('inventoryList.changes')</th>
                                    @if( ViewHelper::universalHasPermission( array(27) ) )
                                        <th class="text-center valign no-sort">@lang('inventoryList.edit')</th>
                                    @else
                                        <th class="text-center valign no-sort">@lang('inventoryList.view')</th>
                                    @endif
                                    <th class="text-center valign no-sort">@lang('inventoryList.history')</th>
                                </thead>
                                <tbody>
                                    @if(count($category->items->count()) )
                                        @foreach($category->items as $k => $item)
                                            <tr>
                                                <td class="text-center valign">
                                                    {{ $item->name }}
                                                </td>
                                                <td class="text-center valign ">
                                                    <a href="#" data-toggle="modal" data-target="#item-taken-{{$item->id}}">
                                                        {{ $item->value }}
                                                    </a>
                                                    {!! ViewHelper::generateInventoryTakenModal($item) !!}  
                                                </td>
                                                <td class="text-center valign ">
                                                    {{ $item->size->name }}
                                                </td>
                                                <td class="text-center valign ">
                                                    {{ $item->updated_at }}
                                                </td>
                                                @if( ViewHelper::universalHasPermission( array(27) ) )
                                                <td class="text-center valign"> 
                                                    <a href="{{route('inventarliste.edit', ['id'=> $item->id])}}">
                                                        @lang('inventoryList.edit')
                                                    </a>   
                                                </td>
                                                @else
                                                    <td class="text-center valign"> 
                                                        <a href="#" data-toggle="modal" data-target="#item-view-{{$item->id}}">
                                                            @lang('inventoryList.view')
                                                        </a>
                                                        {!! ViewHelper::generateInventoryViewModal($item) !!}  
                                                    </td>
                                                @endif
                                                <td class="text-center valign"> 
                                                    <a href="{{url('inventarliste/historie/'.$item->id)}}">
                                                        @lang('inventoryList.history')
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class"valign"></td>
                                            <td class"valign"></td>
                                            <td class"valign"></td>
                                            <td class"valign">Keine Daten vorhanden</td>
                                            <td class"valign"></td>
                                            <td class"valign"></td>
                                        </tr>
                                    @endif
                                
                                </tbody>
                            </table>    
                            </div><!-- end .panel-body -->
                        </div><!-- end .panel-collapse -->
                    
                    </div><!--end .panel-group-->
                @endforeach
        @endif
        
        <!-- search results categories categorie box-->
        <div class="col-xs-12 box-wrapper">
            <h2 class="title">{{ trans('inventoryList.inventorySearchResults') }}</h2>
                <div class="box box-white">
                    <table class="table data-table box-white">
                        <thead>
                            <th class="text-center valign">@lang('inventoryList.name')</th>
                            <th class="text-center valign">@lang('inventoryList.category')</th>
                            <th class="text-center valign">@lang('inventoryList.number')</th>
                            <th class="text-center valign">@lang('inventoryList.size')</th>
                            <th class="text-center valign">@lang('inventoryList.changes')</th>
                            @if( ViewHelper::universalHasPermission( array(27) ) )    
                                <th class="text-center valign no-sort">@lang('inventoryList.edit')</th>
                            @else
                                <th class="text-center valign no-sort">@lang('inventoryList.view')</th>
                            @endif
                            <th class="text-center valign no-sort">@lang('inventoryList.history')</th>
                        </thead>
                        <tbody>
                            @if(count( $searchInventory ) > 0)
                                @foreach($searchInventory as $k => $item)
                                    <tr>
                                        <td class="text-center valign">
                                            {{ $item->name }}
                                        </td>
                                        <td class="text-center valign">
                                            {{ $item->category->name }}
                                        </td>
                                        <td class="text-center valign ">
                                            <a href="#" data-toggle="modal" data-target="#item-taken-{{$item->id}}">
                                                {{ $item->value }}
                                            </a>
                                            {!! ViewHelper::generateInventoryTakenModal($item) !!}  
                                        </td>
                                        <td class="text-center valign ">
                                            {{ $item->size->name }}
                                        </td>
                                        <td class="text-center valign ">
                                            {{ $item->updated_at }}
                                        </td>
                                        @if( ViewHelper::universalHasPermission( array(27) ) )
                                        <td class="text-center valign"> 
                                            <a href="{{route('inventarliste.edit', ['id'=> $item->id])}}">
                                                @lang('inventoryList.edit')
                                            </a>   
                                        </td>
                                        @else
                                            <td class="text-center valign"> 
                                                <a href="#" data-toggle="modal" data-target="#item-view-{{$item->id}}">
                                                    @lang('inventoryList.view')
                                                </a>
                                                {!! ViewHelper::generateInventoryViewModal($item) !!}  
                                            </td>
                                        @endif
                                        <td class="text-center valign"> 
                                            <a href="{{url('inventarliste/historie/'.$item->id)}}">
                                                @lang('inventoryList.history')
                                            </a>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class"valign"></td>
                                    <td class"valign"></td>
                                    <td class"valign"></td>
                                    <td class"valign"></td>
                                    <td class"valign">Keine Daten vorhanden</td>
                                    <td class"valign"></td>
                                    @if( ViewHelper::universalHasPermission( array(27) ) )
                                        <td class="text-center valign no-sort"></td>
                                    @else
                                        <td class="text-center valign no-sort"></tdh>
                                    @endif
                                </tr>
                            @endif
                        
                        </tbody>
                    </table>
                </div><!-- end box -->
                <div class="clearfix"></div>
                <br/>
        </div><!--end  regular categorie box wrapper-->
    @else
        <!-- regular categorie box-->
        @if($mandants)
            @foreach( $mandants as $mandant)
                <div class="panel-group">
                    <div class="panel panel-primary" id="panelInventory{{$mandant->id}}">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                    <a data-toggle="collapse" data-target="#collapseInventory{{$mandant->id}}" class="collapsed transform-normal" 
                                       href="#collapseInventory{{$category->id}}">
                                      {{$mandant->mandant_number}} {{$mandant->name}} 
                                    </a>
                            </h4>
                        </div><!--end .panel-heading -->    
                    </div><!--end .panel.panel-primary -->
                
                    <div id="collapseInventory{{$mandant->id}}" class="panel-collapse collapse">
                        <div class="panel-body box-white">
                            <table class="table data-table box-white">
                            <thead>
                                <tr>
                                    <th  class="text-center valign">@lang('inventoryList.name')</th>
                                    <th  class="text-center valign">@lang('inventoryList.number')</th>
                                    <th class="text-center valign">@lang('inventoryList.size')</th>
                                    <th class="text-center valign">@lang('inventoryList.sellPrice')</th>
                                    <th class="text-center valign no-sort">@lang('inventoryList.dateWithdrawal')</th>
                                    <th class="text-center valign no-sort">@lang('inventoryList.billed')</th>
                                </tr>
                            </thead>
                            <tbody>
                               {{-- @if(count($category->items->count()) )
                                    @foreach($category->items as $k => $item)
                                        <tr>
                                        <td class="text-center valign">
                                            {{ $item->name }}
                                        </td>
                                        <td class="text-center valign ">
                                            <a href="#" data-toggle="modal" data-target="#item-taken-{{$item->id}}">
                                                {{ $item->value }}
                                            </a>
                                            {!! ViewHelper::generateInventoryTakenModal($item) !!}  
                                        </td>
                                        <td class="text-center valign ">
                                            {{ $item->size->name }}
                                        </td>
                                        <td class="text-center valign ">
                                            {{ $item->updated_at }}
                                        </td>
                                        @if( ViewHelper::universalHasPermission( array(27) ) )
                                        <td class="text-center valign"> 
                                            <a href="{{route('inventarliste.edit', ['id'=> $item->id])}}">
                                                @lang('inventoryList.edit')
                                            </a>   
                                        </td>
                                        @else
                                            <td class="text-center valign"> 
                                                <a href="#" data-toggle="modal" data-target="#item-view-{{$item->id}}">
                                                    @lang('inventoryList.view')
                                                </a>
                                                {!! ViewHelper::generateInventoryViewModal($item) !!}  
                                            </td>
                                        @endif
                                        <td class="text-center valign"> 
                                            <a href="{{url('inventarliste/historie/'.$item->id)}}">
                                                @lang('inventoryList.history')
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class"valign"></td>
                                        <td class"valign"></td>
                                        <td class"valign"></td>
                                        <td class"valign">Keine Daten vorhanden</td>
                                        <td class"valign"></td>
                                        <td class"valign"></td>
                                    </tr>
                                @endif --}}
                            
                            </tbody>
                        </table>    
                        </div><!-- end .panel-body -->
                    </div><!-- end .panel-collapse -->
                
                </div><!--end .panel-group-->  
            @endforeach
        @endif
       
    @endif

@stop
