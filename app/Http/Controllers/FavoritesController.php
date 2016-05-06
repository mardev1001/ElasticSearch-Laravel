<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class FavoritesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = '[{"text":"Dokument-197","tags":[4],"nodes":[{"text":"Variante-51","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]},{"text":"Variante-197","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]},{"text":"Variante-190","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]},{"text":"Variante-50","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]}]},{"text":"Dokument-161","tags":[4],"nodes":[{"text":"Variante-51","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]},{"text":"Variante-197","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]},{"text":"Variante-190","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]},{"text":"Variante-50","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]}]},{"text":"Dokument-4","tags":[4],"nodes":[{"text":"Variante-51","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]},{"text":"Variante-197","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]},{"text":"Variante-190","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]},{"text":"Variante-50","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]}]},{"text":"Dokument-90","tags":[4],"nodes":[{"text":"Variante-51","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]},{"text":"Variante-197","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]},{"text":"Variante-190","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]},{"text":"Variante-50","tags":[3],"nodes":[{"text":"Anhang-170","tags":[0]},{"text":"Anhang-8","tags":[0]},{"text":"Anhang-135","tags":[0]}]}]}]'; 
        return view('favoriten.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
