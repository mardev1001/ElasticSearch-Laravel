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
        $mandants = Mandant::all();
        $roles = Role::all();
        return view('benutzer.edit', compact('user', 'mandants', 'roles'));
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
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
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
