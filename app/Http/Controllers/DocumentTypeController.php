<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\DocumentType;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documentTypes = DocumentType::all();
        return view('dokument-typen.index', compact('documentTypes'));
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
        $documentType = new DocumentType();
        $documentType->name = $request->input('name');
        $documentType->document_art = $request->input('document_art');
        $documentType->document_role = $request->input('document_role');
        if($request->has('read_required')) $documentType->read_required = true;
        if($request->has('allow_comments')) $documentType->allow_comments = true;
        if($request->has('visible_navigation')) $documentType->visible_navigation = true;
        $documentType->active = true;
        $documentType->save();
        return back()->with('message', 'Dokument Typ erfolgreich gespeichert.');
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
        $documentType = DocumentType::find($id);
        
        if($request->has('save')){
        
            $documentType->name = $request->input('name');
            
            $documentType->document_art = $request->input('document_art');
            $documentType->document_role = $request->input('document_role');
            
            if($request->has('read_required')) $documentType->read_required = true;
            else $documentType->read_required = false;
            
            if($request->has('allow_comments')) $documentType->allow_comments = true;
            else $documentType->allow_comments = false;
            
            if($request->has('visible_navigation')) $documentType->visible_navigation = true;
            else $documentType->visible_navigation = false;
        }
        
        if($request->has('activate'))
            $documentType->active = !$request->input('activate');
        
        $documentType->save();
        
        return back()->with('message', 'Dokument Typ erfolgreich aktualisiert.');
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
