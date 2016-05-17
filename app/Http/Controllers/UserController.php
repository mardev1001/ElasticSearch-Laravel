<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Requests\BenutzerRequest;

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
    public function __construct() {
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BenutzerRequest $request)
    {
        dd($request);
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
        $user = Users::find($id);
        $mandants = Mandant::all();
        $roles =  Role::all();
        return view('benutzer.edit', compact('user','mandants','roles') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BenutzerRequest $request, $id)
    {
        $user= User::find($id);
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
}
