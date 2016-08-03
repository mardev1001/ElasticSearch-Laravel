<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;

use App\Http\Requests\BenutzerRequest;
use Request as RequestMerge;

use App\User;
use App\Role;
use App\Mandant;
use App\MandantUser;
use App\MandantUserRole;

class UserController extends Controller
{
    
    /**
     * Class constructor
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        // dd( $request->all() );
        $this->validate($request, [
                'username_sso' => 'unique:users',
                'email' => 'unique:users',
            ]);
        $user = User::create($request->all());
        
        $userUpdate = User::find($user->id);
        $userUpdate->update(['created_by' => Auth::user()->id]);
        
        if ($request->file()) {
            $userModel = User::find($user->id);
            $picture = $this->fileUpload($userModel, $this->fileUploadPath, $request->file());
            $userModel->update(['picture' => $picture]);
        }
        
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
        $user = User::find($id);
        $usersAll = User::all();
        $mandantsAll = Mandant::all();
        // $rolesAll = Role::all();
        $rolesAll = Role::where('phone_role', false)->get();
        
        // dd($usersAll);
        
        $mandantUsers = MandantUser::where('user_id', $id)->get();
        $mandants = Mandant::whereIn('id', array_pluck($mandantUsers, 'mandant_id'))->get();
        $mandantUserRoles = MandantUserRole::whereIn('mandant_user_id', array_pluck($mandantUsers, 'id'))->get();
        $roles = MandantUserRole::whereIn('mandant_user_id', array_pluck($mandantUsers, 'id'))->get();
        
        // dd($mandantUserRoles);
        return view('benutzer.edit', compact('user', 'usersAll', 'mandantsAll', 'rolesAll'));
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
        $user->update($request->all());
        
        // Fix Carbon date parsing
        if(!$request->has('birthday')) $user->update(['birthday' => '']);
        if(!$request->has('active_from'))  $user->update(['active_from' => '']); 
        if(!$request->has('active_to'))  $user->update(['active_to' => '']);
        
        if($request->has('active')) $user->update(['active' => true]);
        else $user->update(['active' => false]);
        
        if($request->has('email_reciever')) $user->update(['email_reciever' => true]);
        else $user->update(['email_reciever' => false]);
        
        if ($request->file()) {
            $userModel = User::find($user->id);
            $picture = $this->fileUpload($userModel, $this->fileUploadPath, $request->file());
            $userModel->update(['picture' => $picture]);
        }
        
        // dd($request->all());
        return back()->with('message', 'Benutzer erfolgreich aktualisiert.');
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
        /*
        Wird als zusästzlicher Ersteller bei den Dokumenten angelegt
        Was wird übertragen?
        - Rollen
        - Gelesen/Ungelesen (Dokumente)
        */
        
        // $user = User::find($id);
        // dd($request->all());
        
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
        $checkMandant =  MandantUser::where('mandant_id', $request->get('mandant_id'))->where('user_id', $request->get('user_id'))->count();
        if( $checkMandant > 0)
             return back()->with('message', 'Dieser Mandant ist dem Benutzer bereits zugeordnet.');
        
        $mandantUser = MandantUser::create($request->all());
        
        foreach($request->input('role_id') as $roleId){
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
        $mandantUser = MandantUser::where('id', $request->input('mandant_user_id'))->first();
        if($request->has('save')){
            MandantUserRole::where('mandant_user_id', $request->input('mandant_user_id'))->delete();
            if($request->has('role_id')){
                foreach($request->input('role_id') as $roleId){
                    $mandantUserRole = new MandantUserRole();
                    $mandantUserRole->mandant_user_id = $request->input('mandant_user_id');
                    $mandantUserRole->role_id = $roleId;
                    $mandantUserRole->save();
                }
            }
            // return back()->with('message', 'Rollen erfolgreich aktualisiert.');
            return redirect('benutzer/'.$mandantUser->user_id.'/edit#mandant-role-'.$mandantUser->id)->with('message', 'Rollen erfolgreich aktualisiert.');
        }
        
        if($request->has('remove')){
            MandantUser::where('id', $request->input('mandant_user_id'))->delete();
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
}
