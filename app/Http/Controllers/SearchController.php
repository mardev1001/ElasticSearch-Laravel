<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Repositories\SearchRepository;

use App\Document;
use App\EditorVariant;

class SearchController extends Controller
{
     /**
     * Class constructor
     *
     */
    public function __construct(SearchRepository $searchRepo)
    {
        $this->search =  $searchRepo;
     }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*
        'document_type_id', 'document_status_id', 'user_id','date_created','version',
        'name','owner_user_id','search_tags',
        'summary','date_published','date_modified','date_expired',
        'version_parent','document_group_id','iso_category_id',
        'show_name','adressat_id','betreff','document_replaced_id',
        'date_approved','email_approval','approval_all_roles',
        'approval_all_mandants','pdf_upload'
        */
        
        $parameter = null;
        $results = array();
        $variants = array();
        
        if($request->has('parameter')){
            $parameter = $request->input('parameter');
            
            $documents = Document::where('name', 'LIKE', '%'.$parameter.'%' )
                                ->orWhere('search_tags', 'LIKE', '%'.$parameter.'%' )
                                ->orWhere('summary', 'LIKE', '%'.$parameter.'%' )
                                ->orWhere('betreff', 'LIKE', '%'.$parameter.'%' )
                                ->get();
                                
            $variants = EditorVariant::where('inhalt', 'LIKE', '%'.$parameter.'%')->get();
            
            foreach ($documents as $document) if(!in_array($document, $results)) array_push($results, $document);
            
            foreach ($variants as $variant) if(!in_array($variant->document, $results)) array_push($results, $variant->document);
            
        }
        
        return view('suche.erweitert', compact('parameter','results', 'variants'));
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

    /**
     * Advanced search.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchAdvanced(Request $request)
    {
        return view('suche.erweitert');
    }

    /**
     * Return search results for the phone list users/mandants.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchPhoneList(Request $request)
    {
        $results = $this->search->phonelistSearch($request);
        
        return redirect('telefonliste');
    }
    
}
