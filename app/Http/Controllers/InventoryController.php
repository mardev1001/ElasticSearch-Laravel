<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\ViewHelper;
use Auth;
//Models
use App\User;
use App\Inventory;
use App\InventoryCategory;
use App\InventorySize;
use App\InventoryHistory;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( ViewHelper::universalHasPermission( array(27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
            
        $categories = InventoryCategory::where('active',1)->get();
        $sizes = InventorySize::where('active',1)->get();
        return view('inventarliste.index', compact('categories', 'sizes') );
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if( ViewHelper::universalHasPermission( array(27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        $searchInput = $request->get('search');    
        $seachCategories = InventoryCategory::where('active',1)->where('name','LIKE','%'.$searchInput.'%')->get();
        $seachInventory = Inventory::where('name','LIKE','%'.$searchInput.'%')->get();
        
        //eliminate items from inactive categories
        if($seachInventory != null && count($searchInventory) ){
            foreach( $seachInventory as $k => $inventory ){
                if($inventory->category->active != 1){
                   $searchInventory->forget($inventory->id);
                }
            }  
        }
        
        $categories = InventoryCategory::where('active',1)->get();
        $sizes = InventorySize::all();
        return view('inventarliste.index', compact('categories', 'sizes','seachCategories','seachInventory','searchInput') );
    }
    
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( ViewHelper::universalHasPermission( array(27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
            
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inventory = Inventory::create( $request->all() );
        return redirect()->back()->with( 'messageSecondary', trans('inventoryList.inventoryAdded') );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if( ViewHelper::universalHasPermission( array(27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( ViewHelper::universalHasPermission( array(27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd( $request->all() );
        $item =  Inventory::find($id);
        $oldItem =  Inventory::find($id);
        $item->fill( $request->all() )->save();
        
        $request->merge(['user_id' => Auth::user()->id,'inventory_id' => $id]);
        if( $oldItem->value == $item->value ){
            $request->merge(['value' => null]);
        }
        if( $oldItem->inventory_category_id == $item->inventory_category_id ){
            $request->merge(['inventory_category_id' => null]);
        }
        if( $oldItem->inventory_size_id == $item->inventory_size_id ){
            $request->merge(['inventory_size_id' => null]);
        }
        //prevent filling up the database when all three values are null
        if( !is_null( $request->get('value') ) || !is_null( $request->get('inventory_category_id') ) || !is_null( $request->get('inventory_size_id') )  )
            $history = InventoryHistory::create($request->all());
        
        return redirect()->back()->with( 'messageSecondary', trans('inventoryList.inventoryUpdated') );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        if( ViewHelper::universalHasPermission( array(27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        $categories =  InventoryCategory::all();
        return view('inventarliste.categories', compact('categories') );
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCategories(Request $request)
    {
        $exists = InventoryCategory::where('name',$request->get('name') )->first();
        if( !is_null($exists) )
            return redirect()->back()->with('messageSecondary', trans('inventoryList.categoryExists') );
        $request->merge([ 'active' => 1 ]);    
        $newCategory = InventoryCategory::create($request->all());
        
        return redirect()->back()->with('messageSecondary', trans('inventoryList.categoryCreated') );;
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateCategories(Request $request, $id)
    {
        $category = InventoryCategory::find($id);
        $category->fill( $request->all() )->save();
        return redirect()->back()->with('messageSecondary', trans('inventoryList.categoryUpdated') );;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sizes()
    {
        if( ViewHelper::universalHasPermission( array(27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        $sizes = InventorySize::all();
            
        return view('inventarliste.sizes', compact('sizes') );
    }
    
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postSizes(Request $request)
    {
        $exists = InventorySize::where('name',$request->get('name') )->first();
        if( !is_null($exists) )
            return redirect()->back()->with('messageSecondary', trans('inventoryList.sizeExists') );
        $request->merge([ 'active' => 1 ]);    
        $newCategory = InventorySize::create($request->all());
        
        return redirect()->back()->with('messageSecondary', trans('inventoryList.sizeCreated') );;
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSizes(Request $request, $id)
    {
        $sizes = InventorySize::find($id);
        $sizes->fill( $request->all() )->save();
        return redirect()->back()->with('messageSecondary', trans('inventoryList.sizeUpdated') );;
    }
    
}
