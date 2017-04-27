<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Document;
use App\DocumentType;
use App\DocumentStatus;
use App\DocumentMandantMandant;
use App\DocumentMandant;
use App\MandantUser;
use App\EditorVariant;
use App\Role;
use App\User;
use Auth;

class NoticeController extends Controller
{
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
        $mandantUsers = User::where('active', 1)->get();
        $mitarbeiterUsers = $mandantUsers;

        return view('juristenportal.createNote',
            compact('mandantUsers', 'mitarbeiterUsers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request);
        
        $document_type_id =DocumentType::where('name', ['Notizen'])->first();
        
        $note = new Document;
        $note->document_type_id = $document_type_id->id;
        $note->user_id = Auth::id();
        $note->owner_user_id = $request->user;
        $note->name = $request->username;
        $note->betreff = $request->betreff;
        
        $note->funktion = $request->function;
        $note->nachricht = $request->message;
        $note->telefon = $request->phone;
        $note->ruckruf = $request->has('recall');
        
        $datetime = $request->date.' '.$request->time;
        $date =date('Y-m-d H:i:s', strtotime($datetime));
        $note->created_at = $date;
        
        $note->save();
        
        $editorVariant = new EditorVariant;
        $editorVariant->document_id = $note->id;
        $editorVariant->variant_number = 1;
        $editorVariant->inhalt = $request->content;
        $editorVariant->save();
        
        $documentMandat = new DocumentMandant;
        $documentMandat->document_id = $note->id;
        $documentMandat->editor_variant_id = $editorVariant->id;
        $documentMandat->save();
        
        $newDMR = new DocumentMandantMandant;
        $newDMR->document_mandant_id = $documentMandat->id;
        $newDMR->mandant_id = $request->mandant;
        $newDMR->save();
        
        //echo 'Note Created !';
        //return redirect('notice/'.$note->id);
        
        return view('juristenportal.createNoteStep2', compact('note'));
        
       
    }
    
    public function documentUpload(Request $request)
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
