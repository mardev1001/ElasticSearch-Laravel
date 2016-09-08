<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Request as RequestMerge;

use Auth;
use App\Http\Requests;

use App\Http\Requests\MandantRequest;
use App\Http\Repositories\SearchRepository;

use App\Mandant;
use App\MandantInfo;
use App\MandantUser;
use App\MandantUserRole;
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
    public function __construct(SearchRepository $searchRepo)
    {
         $this->search =  $searchRepo;
        // Define file upload path
        $this->fileUploadPath = public_path() . "/files/pictures/mandants";
        $this->bundeslandList = [
            'Baden-Württemberg',
            'Bayern',
            'Berlin', 
            'Brandenburg',
            'Bremen',
            'Hamburg',
            'Hessen',
            'Mecklenburg-Vorpommern',
            'Niedersachsen',
            'Nordrhein-Westfalen',
            'Rheinland-Pfalz',
            'Saarland',
            'Sachsen',
            'Sachsen-Anhalt',
            'Schleswig-Holstein',
            'Thüringen',
        ];
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $searchParameter = null;
        $deletedUsers = null;
        $deletedMandants = null;
        $roles = Role::all();
        $mandants = Mandant::all();
        // $users = User::all();
        $users = User::orderBy('first_name', 'asc')->get();
        $mandantUsers = MandantUser::all();
        $unassignedUsers = array();
        $unassignedActiveUsers = array();
        $unassignedInactiveUsers = array();
        foreach($users as $user){
            $result = MandantUser::where('user_id', $user->id)->get();
            if($result->isEmpty()) {
                $unassignedUsers[] = $user;
                if($user->active)
                    $unassignedActiveUsers[] = $user;
                else
                    $unassignedInactiveUsers[] = $user;
            }   
        }
        
        return view('mandanten.administration', compact('roles','mandants', 'searchParameter', 'deletedUsers', 'deletedMandants', 'unassignedUsers', 'unassignedActiveUsers', 'unassignedInactiveUsers') );
    }
    
    /**
     * Search the database with the given parameters
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        
        if(!$request->has('search') || $request->method() == "GET")
            return redirect('mandanten');
            
        $search = true;
        $searchParameter = $request->get('search');
        $deletedUsers = $request->has('deleted_users');
        $deletedMandants = $request->has('deleted_mandants');
        
        $roles = Role::all();
       // \DB::enableQueryLog();
        $users = User::where(function ($query) use($searchParameter) {
            $query->where('first_name', 'LIKE', '%'. $searchParameter .'%')
        ->orWhere('last_name', 'LIKE', '%'. $searchParameter .'%')
        ->orWhere('short_name', 'LIKE', '%'. $searchParameter .'%');
        }); 
        if($deletedUsers) $users = $users->onlyTrashed();
        $users = $users ->get();
        //dd( \DB::getQueryLog() );
        $mandants = Mandant::where(function ($query) use($searchParameter) {
            $query-> where('name','LIKE', '%'. $searchParameter .'%')
        ->orWhere('kurzname','LIKE', '%'. $searchParameter .'%')
        ->orWhere('mandant_number','LIKE', '%'. $searchParameter .'%');
        }); 
        if($deletedMandants) $mandants = $mandants->onlyTrashed();
        $mandants = $mandants->get();
        // $mandants = $this->search->phonelistSearch($request);
        return view('mandanten.administration', compact('search', 'searchParameter', 'mandants', 'users', 'roles', 'deletedUsers', 'deletedMandants' ) );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $mandantsAll = Mandant::all();
        $bundeslander = $this->bundeslandList;
        $mandantsAll = Mandant::where('hauptstelle', true)->get();
        return view('formWrapper', compact('data', 'mandantsAll', 'bundeslander'));
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
        $restored = false;
        
        if(Mandant::find($id))
            $data = Mandant::find($id);
        elseif(Mandant::withTrashed()->find($id)){
            Mandant::withTrashed()->find($id)->restore();
            $data = Mandant::find($id);
            $restored = true;
        }
        else $data = null;
        
        $roles = Role::where('phone_role', true)->get();
        $bundeslander = $this->bundeslandList;
        $internalMandantUsers = InternalMandantUser::where('mandant_id', $id)->get();
        // $mandantsAll = Mandant::all();
        $mandantsAll = Mandant::where('hauptstelle', true)->where('id', '!=', $id)->get();
        
        $mandantUsers = array();
        $mandantUsersTmp = MandantUser::all();
        foreach ($mandantUsersTmp as $mandantUser) {
            if($mandantUser->mandant->rights_admin){
                if(!in_array($mandantUser->user, $mandantUsers))
                    array_push($mandantUsers, $mandantUser->user);
            }
        }
        // dd($mandantUsers);
        
        if(isset($data)){
            if($data->edited_by == 0 || $data->edited_by == Auth::user()->id){
                $data->edited_by = Auth::user()->id;
                $data->save();
                return view('formWrapper', compact('data','roles', 'mandantsAll', 'mandantUsers', 'internalMandantUsers','bundeslander'));
            } else return back()->with('message', 'Mandant kann nicht bearbeitet werden.');
        }
        else
            return back()->with('message', 'Mandant existiert nicht oder kann nicht bearbeitet werden.');
    
        
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
        
        if($request->has('mandant-approved')) $mandant->edited_by = 0;
        
        if ($request->file())
            $mandant->logo = $this->fileUpload($mandant, $this->fileUploadPath, $request->file());
        
        $mandantInfos->fill( $request->all() );
        if( $mandant->save() && $mandantInfos->save() ){
            if($mandant->edited_by == 0 ) return redirect('mandanten')->with(['message'=>trans('mandantenForm.savedApproved')]);
            else return redirect('mandanten/'.$id.'/edit#mandant-saved')->with(['message'=>trans('mandantenForm.saved')]);
        }
        //   return back()->with(['message'=>trans('mandantenForm.saved')]);
        return redirect('mandanten/'.$id.'/edit#mandant-saved')->with(['message'=>trans('mandantenForm.saved')]);
        // return back()->with(['message'=>trans('mandantenForm.error')]);
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
            MandantUser::where('mandant_id', $id)->delete();
            $mandant = Mandant::find($id);
            $mandant->delete();
            return back()->with(['message'=>'Mandant wurde erfolgreich entfernt.']);
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
            /* addon for userMandantRoleEdit */
            $mandantUser =  MandantUser::where('mandant_id', $id)->where('user_id',$request->input('user_id'))->first();
            if(isset($mandantUser)){
                $mandantUserRole = new MandantUserRole();
                $mandantUserRole->mandant_user_id = $mandantUser->id;
                $mandantUserRole->role_id =  $request->input('role_id');
                $mandantUserRole->save();
            }
            /* End addon for userMandantRoleEdit */
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
                
                /* addon for userMandantRoleEdit */
                if( $request->has('old_role_id')  ){
                    $mandantUser =  MandantUser::where('mandant_id', $mandantId)->where('user_id',$request->input('user_id'))->first();
                    if(isset($mandantUser)){
                        $mandantUserRole = MandantUserRole::where('mandant_user_id',$mandantUser->id)->where('role_id',$request->input('old_role_id'))->first();
                        $mandantUserRole->role_id =  $request->input('role_id');
                        $mandantUserRole->save();
                    }
                }
                /* End addon for userMandantRoleEdit */
                
                return redirect('mandanten/'.$mandantId.'/edit#internal-role-'.$id)->with('message', trans('mandantenForm.role-updated'));
            }
            
            if($request->has('role-delete')){
                $internalMandantUser->delete();
            
                /* addon for userMandantRoleEdit */
                    $mandantUser =  MandantUser::where('mandant_id', $mandantId)->where('user_id',$request->input('user_id'))->first();
                    if(isset($mandantUser)){
                        $mandantUserRole = MandantUserRole::where('mandant_user_id',$mandantUser->id)
                        ->where('role_id',$request->input('old_role_id'))->delete();
                    }
            /* End addon for userMandantRoleEdit */
                // return back()->with('message', trans('mandantenForm.role-deleted'));
                return redirect('mandanten/'.$mandantId.'/edit#internal-roles')->with('message', trans('mandantenForm.role-deleted'));
            }
        }
        
        return back();
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroyMandantUser(Request $request)
    {
        $requiredUsers = array();
        $requiredRoles = array();
        $requiredRolesResult = array();
        
        $mandantUser = MandantUser::where('user_id', $request->input('user_id'))->where('mandant_id', $request->input('mandant_id'))->first();
        $mandantUserAll = MandantUser::where('mandant_id', $request->input('mandant_id'))->get();
        
        foreach($mandantUser->role as $role)
            if($role->mandant_required || $role->system_role) array_push($requiredRoles, $role);
            // if($role->mandant_required) array_push($requiredRoles, $role);
        
        /* 
        
        select all roles where role is required and user_id = uid, mandant_id = mid -> count
        
        get the current user role_ids
        foreach user role, select all mandant roles, and count the users for the required roles 
        if user count is !(>=1) dont delete
        
        get the current user role_id
        check if the role is required 
        it the role is required, check the number of users for the role_id and mandant_id
        check if role_ids are the same AND that the count of users with that role is >= 1
         
        */
        
        foreach($requiredRoles as $requiredRole){
            
            $roleUsers = array();
            foreach ($mandantUserAll as $mandantUser) {
                foreach($mandantUser->mandantUserRoles as $mandantUserRole){
                    if($requiredRole->id == $mandantUserRole->role_id){
                        if(!in_array($mandantUser, $roleUsers)){
                            array_push($roleUsers, $mandantUser);
                        }
                    }
                }
            }
            $requiredRolesResult[] = array('role_id' => $requiredRole->id, 'user_count' => count($roleUsers));
        }
        
        // dd($requiredRolesResult);

        foreach($requiredRolesResult as $reqRole){
            if($reqRole['user_count'] == 1 )
                return redirect('mandanten')->with('message', 'Benutzer kann nicht entfernt werden.');
        }
        
        
        $mandantUserRoles = MandantUserRole::where('mandant_user_id',$mandantUser->id)->get();
        foreach($mandantUserRoles as $mandantUserRole) {
            // dd($mandantUserRole->roles);
            $mandantUserRole->delete();
        }
        $mandantUser->delete();
        return redirect('mandanten')->with('message', 'Benutzer erfolgreich entfernt.');
        
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