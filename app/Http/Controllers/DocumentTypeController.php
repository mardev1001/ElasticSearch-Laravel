<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Carbon\Carbon;
use Auth;
use App\Helpers\ViewHelper;
use App\User;
use App\MandantUser;
use App\MandantUserRole;
use App\Document;
use App\UserReadDocument;
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
        $documentTypes = DocumentType::orderBy('order_number', 'asc')->get();
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
        
        $documentType->order_number = 1;
        $documentTypeLast = DocumentType::orderBy('id', 'desc')->first();
        if($documentTypeLast) $documentType->order_number = $documentTypeLast->order_number+1;
        
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
        
        return back()->with('message', trans('dokumentTypenForm.updated'));
    }

    /**
     * Increase order number for the item.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sortUp($id)
    {
        // dd('up '.$id);
        $docType = DocumentType::find($id);
        $docTypePrev = DocumentType::where('order_number', $docType->order_number-1)->first();
        
        if($docTypePrev){
            $docType->order_number -= 1;
            $docType->save();
            $docTypePrev->order_number += 1;
            $docTypePrev->save();
        }
        
        return back()->with('message', trans('dokumentTypenForm.updated'));
    }
    
    /**
     * Decrease order number for the item.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function sortDown($id)
    {
        // dd('down '.$id);
        $docType = DocumentType::find($id);
        $docTypeNext = DocumentType::where('order_number', $docType->order_number+1)->first();
        
        if($docTypeNext){
            $docType->order_number += 1;
            $docType->save();
            $docTypeNext->order_number -= 1;
            $docTypeNext->save();
        }
        
        return back()->with('message', trans('dokumentTypenForm.updated'));
    }
    
    /**
     * Reset order number for the items.
     * @return \Illuminate\Http\Response
     */
    public function sortReset(Request $request)
    {
        if($request->get('token') == '!Webbite-1234!'){ 
            $docTypes = DocumentType::all();
            for ($i = 0; $i < sizeof($docTypes); $i++) {
                $type = $docTypes[$i];
                // dd($type);
                $type->update(['order_number' => ($i+1)]);
            }
            return redirect('/')->with('messageSecondary', trans('dokumentTypenForm.updated'));
        } else return back();
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
     * Development sandbox function for testing and debugging
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function devSandbox(Request $request)
    {
        if(ViewHelper::getMandantIsNeptun(Auth::user()->id)) echo 'NEPTUN Mandant';
        
        // $roleIds = array(16); // Assign all roles IDs to populate the MandantUserRole-s
        // $mandantUsersRoles = MandantUserRole::whereIn('role_id', $roleIds)->groupBy('mandant_user_id')->get();
        // $mandantUsers = MandantUser::whereNotIn('id', array_pluck($mandantUsersRoles, 'mandant_user_id'))->orderBy('mandant_id')->orderBy('user_id')->get();
        // foreach($mandantUsers as $mu){
        //     foreach($roleIds as $roleId){
        //         MandantUserRole::create(['mandant_user_id' => $mu->id, 'role_id' => $roleId]);
        //     }
        // }
        
    }
    
    
}
