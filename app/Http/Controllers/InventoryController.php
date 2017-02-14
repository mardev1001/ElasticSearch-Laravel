<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\ViewHelper;
use Auth;
use Mail;
use Carbon\Carbon;
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
        if( ViewHelper::universalHasPermission( array(7,27) ) == false  )
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
        if( ViewHelper::universalHasPermission( array(7,27) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        $searchInput = $request->get('search');    
        $searchCategories = InventoryCategory::where('active',1)->where('name','LIKE','%'.$searchInput.'%')->get();
        $categories = InventoryCategory::where('active',1)->get();
        $activeCategories = $categories->pluck('id')->toArray();
        $searchInventory = Inventory::whereIn('inventory_category_id',$activeCategories)->where('name','LIKE','%'.$searchInput.'%')->get();
        
        
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
        $text = trans('inventoryList.itemCreated').', '.trans('inventoryList.name').': '.$inventory->name;
        $text .=', '.trans('inventoryList.category').': '.$inventory->category->name;
        $text .= ', '.trans('inventoryList.size').': '.$inventory->size->name.', '.trans('inventoryList.number').': '.$inventory->value;
        $text .= ', '.trans('inventoryList.minStock').': '.$inventory->min_stock;
        $text .= ', '.trans('inventoryList.purchasePrice').': '.$inventory->purchase_price;
        $text .= ', '.trans('inventoryList.sellPrice').': '.$inventory->sell_price;
        if(  $inventory->neptun_intern == 1 ){
            $text .= ', '.trans('inventoryList.neptunIntern').': Ja';
        }
        else{
            $text .= ', '.trans('inventoryList.neptunIntern').': Nein';
        }
        $request->merge(['inventory_id'=>$inventory->id, 'user_id'=>Auth::user()->id,'description_text'=>$text]);
        $history = InventoryHistory::create($request->all());
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
        //  dd($request->all() );
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
        else{
            $request->merge( ['is_updated'=> Carbon::now()] );
        }
         if( $request->has('text') && empty($request->get('text') ) ){
            $request->merge(['text'=>null]);
        }
        if( $request->has('mandant_id') && $request->get('mandant_id') == ''){
            $request->merge(['mandant_id'=>null]);
        }
        
        $item->fill( $request->all() )->save();
        
        //change for InventoryHistory
        if( $request->has('taken') ){
           $request->merge(['value'=>$request->get('taken') ]);
        }
        $descriptionString = '';
        $request->merge(['user_id' => Auth::user()->id,'inventory_id' => $id]);
        if( $oldItem->value == $item->value ){
            $request->merge(['value' => null]);
        }
        else{
            $descriptionString .= $this->formatDescriptionString($descriptionString, trans('inventoryList.itemTaken'));
        }
        
        if( $oldItem->inventory_category_id == $item->inventory_category_id ){
            $request->merge(['inventory_category_id' => null]);
        }
        else{
            $descriptionString .= $this->formatDescriptionString($descriptionString, trans('inventoryList.itemUpdated'));
        }
        
        if( $oldItem->inventory_size_id == $item->inventory_size_id ){
            $request->merge(['inventory_size_id' => null]);
        }
        else{
            $descriptionString .= $this->formatDescriptionString($descriptionString, trans('inventoryList.itemUpdated'));
        }
       
        
        //prevent filling up the database when all three values are null
        if(  !is_null( $request->get('value') ) || !is_null( $request->get('inventory_category_id') ) || 
        !is_null( $request->get('inventory_size_id') )  ){
            // dd($request->all() );
            $history = InventoryHistory::create( $request->all() );
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
         if( ViewHelper::universalHasPermission( array(7,27) ) == false  )
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
        return redirect()->back()->with('messageSecondary', trans('inventoryList.sizeUpdated') );
    }
    
    /**
     * Format description text string
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    private function formatDescriptionString($string,$message)
    {
       if(empty($string)){
           return strtoupper($message);
       }
       else{
           return ', '.$message;
       }
    }
    
}
