{{-- Invetarliste index --}}

@extends('master')

@section('page-title') {{ trans('navigation.inventoryList') }} @stop

@section('content')

<!-- add row-->
<div class="row">
    <div class="col-sm-12 ">
        <div class="box-wrapper">
            <h2 class="title"> @lang('inventoryList.newInventory')</h2>
            <div class="box  box-white">
                <div class="row">
                    {!! Form::open(['route' => 'inventarliste.store', 'method'=>'POST']) !!}
                            <div class="col-md-4 col-lg-3">
                                {!! ViewHelper::setInput('name', '',old('name'), 
                                trans('inventoryList.name'), trans('inventoryList.name'), true) !!}
                            </div>
                            <div class="col-md-4 col-lg-3">
                                {!! ViewHelper::setSelect($categories,'inventory_category_id',$data,old('inventory_category_id'),
                                    trans('inventoryList.category'), trans('inventoryList.category'),true ) !!}
                            </div>
                            <div class="col-md-4 col-lg-3">
                                {!! ViewHelper::setSelect($sizes,'inventory_size_id',$data,old('inventory_size_id'),
                                    trans('inventoryList.size'), trans('inventoryList.size'),true ) !!}
                            </div>
                            <div class="col-md-4 col-lg-3">
                                <label class="control-label">
                                   @lang('inventoryList.number')* 
                                </label> 
                               <input type="number" min="0" name="value" class="form-control" required />
                            </div>
                            <div class="col-md-12 col-lg-12">
                                <span class="custom-input-group-btn">
                                    <button type="submit" class="btn btn-primary no-margin-bottom">
                                        {{ trans('inventoryList.add') }} 
                                    </button>
                                </span>
                            </div>
                    {!! Form::close() !!}
                </div>
            </div><!-- end box -->
        </div><!-- end box wrapper-->
    </div>
</div><!-- end add row -->

<!--search row-->
<div class="row">
    <div class="col-sm-12 ">
        <div class="box-wrapper">
            <h2 class="title"> @lang('inventoryList.searchInventoryList')</h2>
            <div class="box  box-white">
                <div class="row">
                    {!! Form::open(['action' => 'InventoryController@search', 'method'=>'POST']) !!}
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
                                </span>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div><!-- end box -->
        </div><!-- end box wrapper-->
    </div>
</div><!-- end search row -->
    @if( $seachCategories || $seachInventory )
        <!-- search results categories categorie box-->
        <div class="col-xs-12 box-wrapper">
            <h2 class="title">{{ trans('inventoryList.categorySearchResults') }}</h2>
            @if( count($seachCategories) )
                @foreach( $seachCategories as $category)
                    <div class="box box-white">
                        <h4>{{ $category->name }}</h4>
                        <table class="table data-table box-white">
                            <thead>
                                <th  class="text-center valign">@lang('inventoryList.name')</th>
                                <th  class="text-center valign">@lang('inventoryList.number')</th>
                                <th class="text-center valign">@lang('inventoryList.size')</th>
                                <th class="text-center valign">@lang('inventoryList.changes')</th>
                                <th class="text-center valign no-sort">@lang('inventoryList.edit')</th>
                                <th class="text-center valign no-sort">@lang('inventoryList.history')</th>
                            </thead>
                            <tbody>
                                @if(count($category->items->count()) > 0)
                                    @foreach($category->items as $k => $item)
                                        <tr>
                                        <td class="text-center valign">
                                            {{ $item->name }}
                                        </td>
                                        <td class="text-center valign ">
                                            {{ $item->value }}
                                        </td>
                                        <td class="text-center valign ">
                                            {{ $item->size->name }}
                                        </td>
                                        <td class="text-center valign ">
                                            {{ $item->updated_at }}
                                        </td>
                                        <td class="text-center valign"> 
                                            <a href="#" data-toggle="modal" data-target="#edit-inventory-{{$item->id}}">
                                                @lang('inventoryList.edit')
                                            </a>
                                            {!! ViewHelper::generateInventoryEditModal($item) !!}    
                                        </td>
                                        <td class="text-center valign"> 
                                            <a href="#" data-toggle="modal" data-target="#history-inventory-{{$item->id}}">
                                                @lang('inventoryList.history')
                                            </a>
                                            {!! ViewHelper::generateInventoryHistoryModal($item) !!}  
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
                    </div><!-- end box -->
                    <div class="clearfix"></div>
                    <br/>
                @endforeach
            @else
                <div class="box box-white">
                    <table class="table data-table box-white">
                        <thead>
                            <th  class="text-center valign">@lang('inventoryList.name')</th>
                            <th  class="text-center valign">@lang('inventoryList.number')</th>
                            <th class="text-center valign">@lang('inventoryList.size')</th>
                            <th class="text-center valign">@lang('inventoryList.changes')</th>
                            <th class="text-center valign no-sort">@lang('inventoryList.edit')</th>
                            <th class="text-center valign no-sort">@lang('inventoryList.history')</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class"valign"></td>
                                <td class"valign"></td>
                                <td class"valign"></td>
                                <td class"valign">Keine Daten vorhanden</td>
                                <td class"valign"></td>
                                <td class"valign"></td>
                            </tr>
                        </tbody>
                    </table>
                    </div><!-- end box -->
            @endif
        </div><!--end  regular categorie box wrapper-->
        
        <!-- search results categories categorie box-->
        <div class="col-xs-12 box-wrapper">
            <h2 class="title">{{ trans('inventoryList.inventorySearchResults') }}</h2>
                <div class="box box-white">
                    <table class="table data-table box-white">
                        <thead>
                            <th  class="text-center valign">@lang('inventoryList.name')</th>
                            <th  class="text-center valign">@lang('inventoryList.number')</th>
                            <th class="text-center valign">@lang('inventoryList.size')</th>
                            <th class="text-center valign">@lang('inventoryList.changes')</th>
                            <th class="text-center valign no-sort">@lang('inventoryList.edit')</th>
                            <th class="text-center valign no-sort">@lang('inventoryList.history')</th>
                        </thead>
                        <tbody>
                            @if(count( $seachInventory ) > 0)
                                @foreach($seachInventory as $k => $item)
                                    <tr>
                                    <td class="text-center valign">
                                        {{ $item->name }}
                                    </td>
                                    <td class="text-center valign ">
                                        {{ $item->value }}
                                    </td>
                                    <td class="text-center valign ">
                                        {{ $item->size->name }}
                                    </td>
                                    <td class="text-center valign ">
                                        {{ $item->updated_at }}
                                    </td>
                                    <td class="text-center valign"> 
                                        <a href="#" data-toggle="modal" data-target="#edit-inventory-{{$item->id}}">
                                            @lang('inventoryList.edit')
                                        </a>
                                        {!! ViewHelper::generateInventoryEditModal($item) !!}    
                                    </td>
                                    <td class="text-center valign"> 
                                        <a href="#" data-toggle="modal" data-target="#history-inventory-{{$item->id}}">
                                            @lang('inventoryList.history')
                                        </a>
                                        {!! ViewHelper::generateInventoryHistoryModal($item) !!}  
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
                </div><!-- end box -->
                <div class="clearfix"></div>
                <br/>
        </div><!--end  regular categorie box wrapper-->
    @else
        <!-- regular categorie box-->
        <div class="col-xs-12 box-wrapper">
            <h2 class="title">{{ trans('inventoryList.overview') }}</h2>
            
            @if($categories)
                @foreach( $categories as $category)
                    <div class="box box-white">
                        <h4>{{ $category->name }}</h4>
                        <table class="table data-table box-white">
                            <thead>
                                <th  class="text-center valign">@lang('inventoryList.name')</th>
                                <th  class="text-center valign">@lang('inventoryList.number')</th>
                                <th class="text-center valign">@lang('inventoryList.size')</th>
                                <th class="text-center valign">@lang('inventoryList.changes')</th>
                                <th class="text-center valign no-sort">@lang('inventoryList.edit')</th>
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
                                            {{ $item->value }}
                                        </td>
                                        <td class="text-center valign ">
                                            {{ $item->size->name }}
                                        </td>
                                        <td class="text-center valign ">
                                            {{ $item->updated_at }}
                                        </td>
                                        <td class="text-center valign"> 
                                            <a href="#" data-toggle="modal" data-target="#edit-inventory-{{$item->id}}">
                                                @lang('inventoryList.edit')
                                            </a>
                                            {!! ViewHelper::generateInventoryEditModal($item) !!}    
                                        </td>
                                        <td class="text-center valign"> 
                                            <a href="#" data-toggle="modal" data-target="#history-inventory-{{$item->id}}">
                                                @lang('inventoryList.history')
                                            </a>
                                            {!! ViewHelper::generateInventoryHistoryModal($item) !!}  
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
                    </div><!-- end box -->
                    <div class="clearfix"></div>
                    <br/>
                @endforeach
             @endif
        </div><!--end  regular categorie box wrapper-->
    @endif

@stop
