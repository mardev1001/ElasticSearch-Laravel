<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Request as RequestMerge;

use App\Http\Requests;

use App\Http\Requests\MandantRequest;

use App\Mandant;
use App\MandantInfo;
use App\User;
use App\Role;

class MandantController extends Controller
{
    
    public function __construct(){
        // 
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $mandants = Mandant::all();
        return view('mandanten.administration', compact('roles','mandants') );
    }
    
    /**
     * Search the database with the given parameters
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search = true;
        $roles = Role::all();
        //$users = User::where('name',$request->get('search'))->get();   
        $mandants = Mandant::where('name','LIKE',$request->get('search'))->get();  
        return view('mandanten.administration', compact('search','mandants','roles') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('formWrapper', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MandantRequest $request)
    {
        session()->flash('message',trans('mandantForm.success'));
        $data = Mandant::create( $request->all() );
         
        
         return redirect('mandanten/'.$data->id.'/edit')->with(['message'=>trans('mandantenForm.success')]);
         
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::all();
        $data = Mandant::find($id);
        return view('formWrapper', compact('data','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MandantRequest $request, $id)
    {
        RequestMerge::merge(['mandant_id' => $id ] );
        $mandant = Mandant::find($id);
        $mandantInfos = MandantInfo::firstOrNew( ['mandant_id' =>$id] );
        $mandant->fill( $request->all() );
        $mandantInfos->fill( $request->all() );
        if( $mandant->save() && $mandantInfos->save() )
          return back()->with(['message'=>trans('mandantenForm.saved')]);
            //dd( $mandantInfos );
            
        return back()->with(['message'=>trans('mandantenForm.error')]);
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
     * Generate and return HTML row
     *
     * @param  array  $id
     * @return \Illuminate\Http\Response
     */
    public function generateUserRole()
    {
        $collections = array();
        $data = '';
        return view('partials.userRole', compact('collections','data'))->render();
    }
}
