@section('page-title') {{ trans('controller.create') }} @stop

<div class="box-wrapper col-sm-12">
    <div class="box  box-white">
        <div class="row">
            <div class="col-md-4 col-lg-3">
                  <div class="form-group">
                    {!! ViewHelper::setInput('name', $data,old('name'), 
                    trans('inventoryList.name'), trans('inventoryList.name'), true) !!}
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                  <div class="form-group">
                    {!! ViewHelper::setSelect($categories,'inventory_category_id',$data,old('inventory_category_id'),
                    trans('inventoryList.category'), trans('inventoryList.category'),true ) !!}
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    {!! ViewHelper::setSelect($sizes,'inventory_size_id',$data,old('inventory_size_id'),
                    trans('inventoryList.size'), trans('inventoryList.size'),true ) !!}
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    {!! ViewHelper::setInput('value', $data,old('value'), 
                    trans('inventoryList.number'), trans('inventoryList.number'), true,'number', array(),array('min'=> 0) ) !!}
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    {!! ViewHelper::setInput('min_stock', $data,old('min_stock'), 
                    trans('inventoryList.minStock'), trans('inventoryList.minStock'), true,'number', array(),array('min'=> 0) ) !!}
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    {!! ViewHelper::setInput('purchase_price', $data,old('purchase_price'), 
                    trans('inventoryList.purchasePrice'), trans('inventoryList.purchasePrice') ) !!}
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    {!! ViewHelper::setInput('sell_price', $data,old('sell_price'), 
                    trans('inventoryList.sellPrice'), trans('inventoryList.sellPrice'), true,'number', array(),array('min'=> 1) ) !!}
                </div>
           </div>
            <div class="col-md-4 col-lg-3">
                <div class="form-group">
                    <br/>
                   {!! ViewHelper::setCheckbox('neptun_intern',$data,old('neptun_intern'),trans('inventoryList.neptunIntern') ) !!}
                </div>
           </div>
        </div>
    </div><!-- end box -->
</div><!-- end box wrapper-->
   
