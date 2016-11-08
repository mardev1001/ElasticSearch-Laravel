<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

use App\Helpers\ViewHelper;
use App\Http\Requests\BenutzerRequest;
use Request as RequestMerge;

use App\User;
use App\Role;
use App\Mandant;
use App\MandantUser;
use App\MandantUserRole;
use App\InternalMandantUser;


use App\Http\Repositories\UtilityRepository;

class UserController extends Controller
{
    
    /**
     * Class constructor
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(UtilityRepository $utilities)
    {
        $this->utils = $utilities;
        // Define file upload path
        $this->fileUploadPath = public_path() . "/files/pictures/users";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $uid = Auth::user()->id;
        $searchParameter = null;
        $deletedUsers = null;
        $deletedMandants = null;
        if( ViewHelper::universalHasPermission( array(2,4), false ) == false )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
            
        $mandantUserIds = MandantUserRole::whereIn('role_id', array(2,4))->pluck('mandant_user_id')->toArray();
        $mandantIds = MandantUser::where('user_id', $uid)->whereIn('id', $mandantUserIds)->pluck('mandant_id')->toArray();
        $roles = Role::all();
        $mandants = Mandant::whereIn('id', $mandantIds)->get();
            
        return view('mandanten.individualAdministration', compact('mandants','roles',
        'searchParameter','deletedUsers','deletedMandants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!$this->utils->universalHasPermission([6,17])) 
            return redirect('/')->with('message', trans('documentForm.noPermission'));
            
        return view('benutzer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(BenutzerRequest $request)
    {
        if(!$request->has('username_sso') || $request->get('username_sso') == '' || empty($request->get('username_sso')) )
             $request->merge(array('username_sso' => null) );
            
        
        // dd( $request->all() );
        $user = User::create($request->all());
        
        $userUpdate = User::find($user->id);
        $userUpdate->created_by = Auth::user()->id;
        $userUpdate->last_login = null;
        $userUpdate->save();
        
        if ($request->file()) {
            $userModel = User::find($user->id);
            $picture = $this->fileUpload($userModel, $this->fileUploadPath, $request->file());
            $userModel->update(['picture' => $picture]);
        }
        
        // dd($userUpdate);
        return redirect()->route('benutzer.edit', [$user])->with('message', 'Benutzer erfolgreich gespeichert.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!$this->utils->universalHasPermission([6,17])) 
            return redirect('/')->with('message', trans('documentForm.noPermission'));
            
        $restored = false;
    
        if(User::find($id))
            $user = User::find($id);
        elseif(User::withTrashed()->find($id)){
            User::withTrashed()->find($id)->restore();
            $user = User::find($id);
            $restored = true;
        }
        else $user = null;
        
        // $user = User::find($id);
        $usersAll = User::all();
        $mandantsAll = Mandant::all();
        // $rolesAll = Role::all();
        // $rolesAll = Role::where('phone_role', false)->get();
        $rolesAll = Role::all();
        
        $mandantUsers = MandantUser::where('user_id', $id)->get();
        $mandants = Mandant::whereIn('id', array_pluck($mandantUsers, 'mandant_id'))->get();
        $mandantUserRoles = MandantUserRole::whereIn('mandant_user_id', array_pluck($mandantUsers, 'id'))->get();
        $roles = MandantUserRole::whereIn('mandant_user_id', array_pluck($mandantUsers, 'id'))->get();
        
        // dd($mandantUserRoles);
        if(isset($user))
            return view('benutzer.edit', compact('user', 'usersAll', 'mandantsAll', 'rolesAll'));
        else
            return back()->with('message', 'Benutzer existiert nicht oder kann nicht bearbeitet werden.');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPartner(Request $request, $id, $mandant_id)
    {
        
        $mandantId = $mandant_id;
        
        if(!$this->utils->universalHasPermission([2,4]))
            return redirect('/')->with('message', trans('documentForm.noPermission'));
            
        $restored = false;
    
        if(User::find($id))
            $user = User::find($id);
        elseif(User::withTrashed()->find($id)){
            User::withTrashed()->find($id)->restore();
            $user = User::find($id);
            $restored = true;
        }
        else $user = null;
        
        // $user = User::find($id);
        $usersAll = User::all();
        $mandantsAll = Mandant::all();
        // $rolesAll = Role::where('phone_role', false)->get();
        $rolesAll = Role::where('mandant_role', 1)->get();
        
        $loggedUserRoles = MandantUserRole::where('mandant_user_id', MandantUser::where('user_id', Auth::user()->id)->where('mandant_id', $mandantId)->first()->id)->get();
        
        foreach ($loggedUserRoles as $tmp) {
            if(!in_array($tmp->role_id, array_pluck($rolesAll,'id')))
               $rolesAll->push(Role::find($tmp->role_id));
        }
    
        // dd($rolesAll);
        
        $mandantUsers = MandantUser::where('user_id', $id)->where('mandant_id', $mandantId)->get();
        $mandants = Mandant::whereIn('id', array_pluck($mandantUsers, 'mandant_id'))->get();
        $mandantUserRoles = MandantUserRole::whereIn('mandant_user_id', array_pluck($mandantUsers, 'id'))->get();
        $roles = MandantUserRole::whereIn('mandant_user_id', array_pluck($mandantUsers, 'id'))->get();
        
        // dd($mandantUserRoles);
        if(isset($user))
            return view('benutzer.editPartner', compact('user', 'usersAll', 'mandantsAll', 'rolesAll', 'mandantUsers'));
        else
            return back()->with('message', 'Benutzer existiert nicht oder kann nicht bearbeitet werden.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BenutzerRequest $request, $id)
    {
       
        $user = User::find($id);
        // $user->update($request->all());
         if(!$request->has('username_sso') || $request->get('username_sso') == '' || empty($request->get('username_sso')) )
             $request->merge(array('username_sso' => null) );
        $user->update($request->except(['password', 'password_repeat']));
        
       
        
        // Fix Carbon date parsing
        if(!$request->has('birthday')) $user->update(['birthday' => '']);
        if(!$request->has('active_from'))  $user->update(['active_from' => '']); 
        if(!$request->has('active_to'))  $user->update(['active_to' => '']);
        
        if($request->has('active')) $user->update(['active' => true]);
        else $user->update(['active' => false]);
        
        if($request->has('email_reciever')) $user->update(['email_reciever' => true]);
        else $user->update(['email_reciever' => false]);

        $pass = $request->input('password');
        $passRep = $request->input('password_repeat');
        
        if(!empty($pass) && ($pass == $passRep))
            $user->update(['password' => $pass]);
        
        if ($request->file()) {
            $userModel = User::find($user->id);
            $picture = $this->fileUpload($userModel, $this->fileUploadPath, $request->file());
            $userModel->update(['picture' => $picture]);
        }
        
        // dd($request->all());
        // dd($user->getAttributes());
        // $user->save();
        
        if($request->has('partner-role'))
            return back()->with('message', 'Benutzer erfolgreich aktualisiert.');
        return back()->with('message', 'Benutzer erfolgreich aktualisiert.');
    }
    

    
     /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $id = Auth::user()->id;
    
        if(User::find($id))
            $user = User::find($id);
        elseif(User::withTrashed()->find($id)){
            User::withTrashed()->find($id)->restore();
            $user = User::find($id);
            $restored = true;
        }
        else $user = null;
        
        $rolesAll = Role::all();
        
        if(isset($user))
            return view('benutzer.profile', compact('user', 'rolesAll'));
        else
            return back()->with('message', 'Benutzer existiert nicht oder kann nicht bearbeitet werden.');
    }
    
    /**
     * Transfer roles from one user to another.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function userRoleTransfer(Request $request)
    {
        
        // dd($request->all() );
        $user = $request->input('user_id'); //primary user
        $selectedUser = $request->input('user_transfer_id'); //dropdown user
        $userMandants = MandantUser::where('user_id',$user)->pluck('mandant_id')->toArray(); //pUser Mandants
        
        $mandantUsers = MandantUser::where('user_id',$selectedUser)->get(); //ddUser in user mandant
        
        foreach( $mandantUsers as $mandantUser ){
            $sUserM = MandantUser::where('user_id',$user)->where('mandant_id',$mandantUser->mandant_id)->first();
            if( !count($sUserM) )
                $sUserM = MandantUser::create( [ 'user_id' => $user, 'mandant_id'=> $mandantUser->mandant_id ] );
            
            // dd($sUserM);
            $selectedMURoles = MandantUserRole::where('mandant_user_id',$mandantUser->id)->get();
            
            $userMandantUserRoles = MandantUserRole::where('mandant_user_id', $sUserM->id)->pluck('role_id')->toArray();
            foreach( $selectedMURoles as $smur )
                if( !in_array($smur->role_id, $userMandantUserRoles) )
                    $create = MandantUserRole::create( [ 'mandant_user_id' => $sUserM->id, 'role_id' => $smur->role_id ] ); 
        }
        return back()->with('message', 'Rollenübertragung erfolgreich abgeschlossen.');
    }
    
    /**
     * Assign a mandant and roles for the user
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function userMandantRoleAdd(Request $request)
    {
        // dd($request->all());
        $checkMandant =  MandantUser::where('mandant_id', $request->get('mandant_id'))->where('user_id', $request->get('user_id'))->count();
        if( $checkMandant > 0)
             return back()->with('message', 'Dieser Mandant ist dem Benutzer bereits zugeordnet.');
        
        $mandantUser = MandantUser::create($request->all());
        
        foreach($request->input('role_id') as $roleId){
            
            $tmpRole = Role::find($roleId);
            
            // if($tmpRole->phone_role){
            //     $internalMandantUser = InternalMandantUser::create([
            //         'mandant_id' => $request->get('mandant_id'), 
            //         'role_id' => $roleId, 
            //         'user_id' => $request->get('user_id'),
            //     ]);
            // }
            
            $mandantUserRole = new MandantUserRole();
            $mandantUserRole->mandant_user_id = $mandantUser->id;
            $mandantUserRole->role_id = $roleId;
            $mandantUserRole->save();
            
        }
        // return back()->with('message', 'Mandant und Rollen erfolgreich gespeichert.');
        return redirect('benutzer/'.$mandantUser->user_id.'/edit#mandant-role-'.$mandantUser->id)->with('message', 'Mandant und Rollen erfolgreich gespeichert.');
    }
    
    /**
     * Update mandant roles for the user
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function userMandantRoleEdit(Request $request)
    {
        // dd($request->all());
        $mandantUser = MandantUser::where('id', $request->input('mandant_user_id'))->first();
        if($request->has('save')){
            
            // dd( $request->all() );
            $clearedRoles = $request->has('role_id');
            $mandantUserRoles = MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->pluck('role_id')->toArray();
            $noDeleteArr = array();
            
            if($request->has('partner-role')){
                // Delete all "partner" roles for the "mandant_user_id"
                $partnerRoles = Role::where('mandant_role', 1)->pluck('id')->toArray();
                // dd($partnerRoles);
                // MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->whereIn('role_id', $partnerRoles)->delete();
                MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->delete();
            } else {
                
                if(!count($request->input('role_id'))) 
                    return back()->with('message', 'Rollen dürfen nicht leer sein.');
            
                $temp = $this->preventDeleteRoles(
                    $mandantUserRoles, $request->input('role_id'), 
                    $request->input('mandant_id'),  
                    $request->input('mandant_user_id')
                );
                
                if( count($temp) )
                    $noDeleteArr = $temp;
                
                $del = MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->whereNotIn('role_id',$noDeleteArr)->delete();
            }
            
            // $internalRoleArray = InternalMandantUser::where('user_id', $request->input('user_id'))->pluck('role_id')->toArray();
            // $exists = InternalMandantUser::where('user_id', $request->input('user_id'))->where('mandant_id', $request->input('mandant_id'))->delete();
            // dd($internalRoleArray);
            
            $requestRoleArray = array();
            if($request->has('role_id')){
                foreach($request->input('role_id') as $roleId){
                    $mandantUserRole = new MandantUserRole();
                    $mandantUserRole->mandant_user_id = $request->input('mandant_user_id');
                    $mandantUserRole->role_id = $roleId;
                    $mandantUserRole->save();
                    
                    // $roleTest = Role::find($roleId);
                    // if( $roleTest->phone_role == true ){
                    //   $checkInternal = InternalMandantUser::where('mandant_id',$request->input('mandant_id'))
                    //   ->where('role_id',$roleId)->where('user_id',$request->input('user_id'))->get();
                    //   if( count($checkInternal) < 1 ){
                    //     $internalMandantUser = InternalMandantUser::create(['mandant_id' => $request->input('mandant_id'), 
                    //     'role_id' => $roleId, 'user_id' => $request->input('user_id')]);
                    //     // dd($internalMandantUser);
                    //   }
                    // }
                }
                //   dd($requestRoleArray);
                // $difference = array_diff($requestRoleArray,$internalRoleArray);
               
                // dd($internalRoleArray);
                 
                // dd($exists);
                // InternalMandantUser::where('user_id', $request->input('user_id'))->whereIn('role_id',$difference)->delete();
                
             }
            // return back()->with('message', 'Rollen erfolgreich aktualisiert.');
            $message = '';
           
            if( count($noDeleteArr) )
                $message .= trans('benutzerForm.lastMandantRole').'<br/>';
            $message .= 'Rollen erfolgreich aktualisiert.';
            //  dd($message);
            
            if($request->has('partner-role'))
                return back()->with('message', $message);
                
            return redirect('benutzer/'.$mandantUser->user_id.'/edit#mandant-role-'.$mandantUser->id)->with('message', $message);
        }
        
        if($request->has('remove')){
            $mandantUserRoles = MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->pluck('role_id')->toArray();
            $noDeleteArr = array();

            if($request->has('role_id')){
                $temp = $this->preventDeleteRoles($mandantUserRoles,$request->input('role_id'),
                    $request->input('mandant_id'),  $request->input('mandant_user_id'), true );
                
                if( count($temp) )
                    $noDeleteArr = $temp;
            }
            
            $message = '';
            if( count($noDeleteArr) ){
                $message .= trans('benutzerForm.lastMandantRole');
                return redirect('benutzer/'.$mandantUser->user_id.'/edit#mandant-role-'.$mandantUser->id)->with('message', $message);
            }
            MandantUser::where('id', $request->input('mandant_user_id'))->delete();
            MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->delete();
            
            // if($request->has('role_id')){
            //     foreach($request->input('role_id') as $roleId){
            //         $roleTest = Role::find($roleId);
            //         if( $roleTest->phone_role == true ){
            //           InternalMandantUser::where('mandant_id',$request->input('mandant_id'))
            //             ->where('role_id',$roleId)->where('user_id',$request->input('user_id'))->delete();
            //         }
            //     }
            //  }
            
            // return back()->with('message', 'Rollen wurden entfernt.');
            return redirect('benutzer/'.$mandantUser->user_id.'/edit#mandants-roles')->with('message', 'Rollen wurden entfernt.');
        }
    }

    /**
     * Activate or deactivate a user
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function userActivate(Request $request)
    {
      
        User::find($request->input('user_id'))->update(['active' => !$request->input('active')]);
        return back()->with('message', 'Benutzer erfolgreich aktualisiert.')->with('mandantChanged',$request->get('mandant_id'));
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if($user->id == 1) return back()->with('message', 'Benutzer kann nicht entfernt werden.');
        
        $mandantUsers = MandantUser::where('user_id', $id)->get();
        
        foreach($mandantUsers as $mandantUser) {
            $mandantUserRoles = MandantUserRole::where('mandant_user_id',$mandantUser->id)->get();    
            foreach($mandantUserRoles as $mandantUserRole) {
                $mandantUserRole->delete();
            }
            $mandantUser->delete();
        }
        
        $user->delete();
        
        return redirect('mandanten')->with('message', 'Benutzer erfolgreich entfernt.');
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
                return redirect('benutzer')->with('message', 'Benutzer kann nicht entfernt werden.');
        }
        
        
        $mandantUserRoles = MandantUserRole::where('mandant_user_id',$mandantUser->id)->get();
        foreach($mandantUserRoles as $mandantUserRole) {
            // dd($mandantUserRole->roles);
            $mandantUserRole->delete();
        }
        $mandantUser->delete();
        InternalMandantUser::where('user_id', $request->input('user_id'))->where('mandant_id', $request->input('mandant_id'))->delete();
        
        return redirect('benutzer')->with('message', 'Benutzer erfolgreich entfernt.');
        
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
        $newName = str_slug('user-'. $model->id) . '.' . $file->getClientOriginalExtension();
        $path = $folder . '/' . $newName;
        $filename = $file->getClientOriginalName();
        $uploadSuccess = $file->move($folder, $newName);
        \File::delete($folder . '/' . $filename);
        return $newName;
    }
    
    /**
     * Check if roles are system roles and if this user is the only user in mandat with this role
     *
     * @param  array $roleArray
     * @return array $roleArray
     */
    public function preventDeleteRoles($mandantUserRoles ,$roleArray,$mandantId,$mandatUserId, $deleteCheck=false)
    {
        $difference = array_diff($mandantUserRoles,$roleArray);
        $noDeleteArr = array();
        $midCheck = array();
        $midCheck2 = array();
        if($deleteCheck == false){
            foreach($difference as $k => $role) {
                $roleDb = Role::find($role);
                if( $roleDb->mandant_required == 1){
                    $mUsers = MandantUser::where('mandant_id', $mandantId)->get();
                    // $mUsers = MandantUser::where('mandant_user_id', $mandantId)->where('role_id',$role)->get();
                    // dd($mUsers);
                    $count = 0;
                    foreach($mUsers as $mUser){
                        $mur = MandantUserRole::where('mandant_user_id', $mUser->id)->where('role_id',$role)->first();
                        if($mur != null ){
                          $midCheck2[] = $mur->mandant_user_id;
                            if( count($mur) ){
                                   
                                   if( in_array($mur->mandant_user_id, $midCheck) == false){
                                        $count++;
                                        $midCheck[] = $mur->mandant_user_id;
                                        
                                    }
                          }  
                        }
                         
                    }
                        if( $count <= 1 )
                            $noDeleteArr[] = $role;
                        
                      
               }
            }
        }
        else{
            foreach($mandantUserRoles as $role){
                $roleDb = Role::find($role);
                if( $roleDb->mandant_required == 1){
                    $mUsers = MandantUser::where('mandant_id', $mandantId)->get();
                    // $mUsers = MandantUser::where('mandant_user_id', $mandantId)->where('role_id',$role)->get();
                    // dd($mUsers);
                    $count = 0;
                    foreach($mUsers as $mUser){
                        $mur = MandantUserRole::where('mandant_user_id', $mUser->id)->where('role_id',$role)->first();
                        if($mur != null ){
                          $midCheck2[] = $mur->mandant_user_id;
                            if( count($mur) ){
                                   
                                   if( in_array($mur->mandant_user_id, $midCheck) == false){
                                        $count++;
                                        $midCheck[] = $mur->mandant_user_id;
                                        
                                    }
                          }  
                        }
                         
                    }
                        if( $count <= 1 )
                            $noDeleteArr[] = $role;
                        
                      
               }    
                }
        }       
        return $noDeleteArr;
        
    }

}
