<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Request as RequestMerge;

use App\Http\Requests;

use App\Http\Requests\MandantRequest;

use App\Mandant;
use App\MandantInfo;
use App\MandantUser;
use App\User;
use App\Role;
use App\InternalMandantUser;

class MandantController extends Controller
{
    
    /**
     * Class constructor
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        // Define file upload path
        $this->fileUploadPath = public_path() . "/files/pictures/mandants";
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
        $users = User::all();
        $mandantUsers = MandantUser::all();
        $unassignedUsers = array();
        foreach($users as $user){
            $result = MandantUser::where('user_id', $user->id)->get();
            if($result->isEmpty()) $unassignedUsers[] = $user;
        }
        
        return view('mandanten.administration', compact('roles','mandants','unassignedUsers') );
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
        // $mandantsAll = Mandant::all();
        $mandantsAll = Mandant::where('hauptstelle', true)->get();
        return view('formWrapper', compact('data', 'mandantsAll'));
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
        if($request->has('hauptstelle')) $data->mandant_id_hauptstelle = null;
        $data->save();
        
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
        $roles = Role::where('phone_role', true)->get();
        $data = Mandant::find($id);
        $mandantUsers = User::all();
        $internalMandantUsers = InternalMandantUser::where('mandant_id', $id)->get();
        // $mandantsAll = Mandant::all();
        $mandantsAll = Mandant::where('hauptstelle', true)->where('id', '!=', $id)->get();
        return view('formWrapper', compact('data','roles', 'mandantsAll', 'mandantUsers', 'internalMandantUsers'));
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
        
        $mandant->hauptstelle = $request->has('hauptstelle');
        $mandant->rights_wiki = $request->has('rights_wiki');
        $mandant->rights_admin = $request->has('rights_admin');
        $mandantInfos->unbefristet = $request->has('unbefristet');
        if($mandant->hauptstelle) $mandant->mandant_id_hauptstelle = null;
        
        
        if ($request->file())
            $mandant->logo = $this->fileUpload($mandant, $this->fileUploadPath, $request->file());
        
        $mandantInfos->fill( $request->all() );
        if( $mandant->save() && $mandantInfos->save() )
          return back()->with(['message'=>trans('mandantenForm.saved')]);
        return back()->with(['message'=>trans('mandantenForm.error')]);
    }


    /**
     * Activate or deactivate a mandant
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function mandantActivate(Request $request)
    {
        
        $mandant = Mandant::find($request->input('mandant_id'))->update(['active' => !(bool)$request->input('active')]);
        $users = Mandant::find($request->input('mandant_id'))->users;
        /*Deactivate all users which are not in another company*/
        foreach($users as $user){
            $userMandants = MandantUser::where('user_id',$user->id)->count();
            if($userMandants < 2){
                $user->active = 0;
                $user->save();
            }
        }
        /* End Deactivate all users which are not in another company*/
        return back()->with(['message'=>trans('mandantenForm.saved')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($id != 1) {
            $mandant = Mandant::find($id);
        }
        return back()->with(['message'=>'Mandant kann nicht gelöscht werden.']);
    }
    
    /**
     * Create internal roles/users for the mandant
     *
     * @param  Request  $request
     * @param  array  $id
     * @return \Illuminate\Http\Response
     */
    public function createInternalMandantUser(Request $request, $id)
    {
        $internalMandantUser = InternalMandantUser::create(['mandant_id' => $id, 'role_id' => $request->input('role_id'), 'user_id' => $request->input('user_id'),]);
        // return back()->with('message', trans('mandantenForm.role-added'));
        return redirect('mandanten/'.$id.'/edit#internal-role-'.$internalMandantUser->id)->with('message', trans('mandantenForm.role-added'));
    }
    
    /**
     * Update or delete internal roles/users for the mandant
     *
     * @param  Request  $request
     * @param  array  $id
     * @return \Illuminate\Http\Response
     */
    public function editInternalMandantUser(Request $request, $id)
    {
        // var_dump($id);
        // dd($request);
        
        if($request->has('internal_mandant_user_id')){
            
            $id = $request->input('internal_mandant_user_id');
            
            $internalMandantUser = InternalMandantUser::where('id', $id)->first();
            $mandantId = $internalMandantUser->mandant_id;
            
            if($request->has('role-update')){
                InternalMandantUser::where('id', $id)->update([ 'role_id' => $request->input('role_id'), 'user_id' => $request->input('user_id') ]);
                // return back()->with('message', trans('mandantenForm.role-updated'));
                return redirect('mandanten/'.$mandantId.'/edit#internal-role-'.$id)->with('message', trans('mandantenForm.role-updated'));
            }
            
            if($request->has('role-delete')){
                $internalMandantUser->delete();
                // return back()->with('message', trans('mandantenForm.role-deleted'));
                return redirect('mandanten/'.$mandantId.'/edit#internal-roles')->with('message', trans('mandantenForm.role-deleted'));
            }
        }
        
        return back();
    }

    private function fileUpload($model, $path, $files)
    {
        if (is_array($files)) {
            $uploadedNames = array();
            foreach ($files as $file) {
                $uploadedNames = $this->moveUploaded($file, $path, $model);
            }
        } else
            $uploadedNames = $this->moveUploaded($files, $path, $model);
        return $uploadedNames;
    }

    private function moveUploaded($file, $folder, $model)
    {
        $newName = str_slug('mandant-'. $model->id) . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $newName;
        $filename = $file->getClientOriginalName();
        $uploadSuccess = $file->move($folder, $newName);
        \File::delete($folder . '/' . $filename);
        return $newName;
    }
    
}