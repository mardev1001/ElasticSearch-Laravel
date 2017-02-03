<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\ViewHelper;
use Auth;
use Mail;
//Models
use App\User;
use App\MandantUser;
use App\MandantUserRole;
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
        if( ViewHelper::universalHasPermission( array(6,27) ) == false  )
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
        if( ViewHelper::universalHasPermission( array(6,27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        $searchInput = $request->get('search');    
        $searchCategories = InventoryCategory::where('active',1)->where('name','LIKE','%'.$searchInput.'%')->get();
        $searchInventory = Inventory::where('name','LIKE','%'.$searchInput.'%')->get();
        
        $categories = InventoryCategory::where('active',1)->get();
        $sizes = InventorySize::all();
        return view('inventarliste.index', compact('categories', 'sizes','searchCategories','searchInventory','searchInput') );
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
        
        $categories = InventoryCategory::where('active',1)->get();
        $sizes = InventorySize::where('active',1)->get();
        return view('formWrapper', compact('categories', 'sizes') );
            
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
        $data = Inventory::find($id);    
        $categories = InventoryCategory::where('active',1)->get();
        $sizes = InventorySize::where('active',1)->get();
        return view('formWrapper', compact('data','categories', 'sizes') );
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
        if( !$request->has('neptun_intern') && !$request->has('taken')){
            $request->merge(['neptun_intern'=> 0]);
        }
        if( $request->has('taken') ){
            $newValue = $item->value - intval($request->get('taken') );
            if( $newValue < 0){
                return redirect()->back()->with( 'messageSecondary', trans('inventoryList.lowerThanZero') );
            }
            $request->merge(['value'=>$newValue ]);
        }
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
        if(  !is_null( $request->get('value') ) || !is_null( $request->get('inventory_category_id') ) || 
        !is_null( $request->get('inventory_size_id') )  ){
            $history = InventoryHistory::create($request->all());
            
            //send email if value under the database marked value
            if( (!is_null( $request->get('value') ) && $request->has('taken') ) && $item->min_stock >= $item->value ){
                $request = $request->all();
                $from = new \StdClass();
                $from->name = 'Informationsservice';
                $from->email = 'info@neptun.de';
                $toUser = User::find($request['to_user']);
                $request['logo'] = asset('/img/logo-neptun-new.png');
                $request['from'] =  $from->email;
                
                $request['subject'] = trans('inventoryList.emailSubject');
                $template = view('email.lowStock' ,compact('request', 'item') )->render();
                $mandantUserIds = MandantUserRole::where('role_id', 27)->pluck('mandant_user_id')->toArray();
                $mandatUsers =  MandantUser::whereIn('id',$mandantUserIds)->pluck('user_id')->toArray();
                // dd($from);
                 
                if( count($mandatUsers) ){
                     
                    foreach($mandatUsers as $user){
                        $request['to'] = $to = User::find($user); 
                        // dd($request);
                        $sent= Mail::send([], [], function ($message) use($template, $request, $from,$to, $item){
                            $message->from(  $from->email, $from->name )
                            ->to( $to->email, $to->first_name.' '.$to->last_name )
                            ->subject($request['subject'] )
                            ->setBody($template, 'text/html');
                        }); 
                    }
                }
                
            }
           
        }
        
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyCategory($id)
    {
         if( ViewHelper::universalHasPermission( array(27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        $category = InventoryCategory::find($id);
        
        if( !is_null($category) ){
            $category->delete();
        }
        
        return redirect()->back()->with('messageSecondary', trans('inventoryList.invetoryCategoryDeleted'));    
        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroySize($id)
    {
         if( ViewHelper::universalHasPermission( array(27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        $size = InventorySize::find($id);
        if( !is_null($size) ){
            $size->delete();
        }
        
        return redirect()->back()->with('messageSecondary', trans('inventoryList.invetorySizeDeleted'));  
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function history($itemId)
    {
         if( ViewHelper::universalHasPermission( array(6,27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));   
        $item =  Inventory::find($itemId);
        $histories =  InventoryHistory::where('inventory_id',$itemId)
        ->orderBy('updated_at','desc')->paginate(20);
        return view('inventarliste.history', compact('histories','item') );
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
         if( ViewHelper::universalHasPermission( array(27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
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
         if( ViewHelper::universalHasPermission( array(27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
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
