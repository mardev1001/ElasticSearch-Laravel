<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Repositories\SearchRepository;

use App\Document;
use App\DocumentType;
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
        $documentTypes = DocumentType::all();
        
        if($request->has('parameter')){
            $parameter = $request->input('parameter');
            
            $documents = Document::where('name', 'LIKE', '%'.$parameter.'%' )
                                ->orWhere('search_tags', 'LIKE', '%'.$parameter.'%' )
                                ->orWhere('summary', 'LIKE', '%'.$parameter.'%' )
                                ->orWhere('betreff', 'LIKE', '%'.$parameter.'%' )
                                ->get();
                                
            $variants = EditorVariant::where('inhalt', 'LIKE', '%'.$parameter.'%')->get();
            
            foreach ($documents as $document) if(!in_array($document, $results)) array_push($results, $document);
            
            if(count($variants)){
                foreach ($variants as $variant){
                    if(!in_array($variant->document, $results)) 
                        array_push($results, $variant->document);
                }
            } else {
                $variants = EditorVariant::all();
            }
        }
        
        return view('suche.erweitert', compact('parameter','results', 'variants','documentTypes'));
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
        // dd($request);
        $emptySearch = true;
        $inputs = $request->all();
        foreach ($inputs as $input) if(!empty($input)) $emptySearch = false;
        
        $results = array();
        $variants = array();
        $documentTypes = DocumentType::all();
        
        $name = $request->input('name');
        $betreff = $request->input('betreff');
        $summary = $request->input('beschreibung');
        $inhalt = $request->input('inhalt');
        $search_tags = $request->input('tags');
        // $date_from = $request->input('datum_von');
        $date_from = strlen($request->input('datum_von')) ? Carbon::parse($request->input('datum_von'))->toDateTimeString()  : null;
        // $date_to = $request->input('datum_bis');
        $date_to = strlen($request->input('datum_bis')) ? Carbon::parse($request->input('datum_bis'))->toDateTimeString()  : null;
        $document_type = $request->input('document_type');
        $wiki = $request->has('wiki');
        $history =  $request->has('history');
        
        // dd($request->all());
        
        $documents = Document::where('id', '>', 0);
        
        if(!empty($name)) $documents->where('name', 'LIKE', '%'.$name.'%');
        if(!empty($betreff))  $documents->where('betreff', 'LIKE', '%'.$betreff.'%' );
        if(!empty($summary))  $documents->where('summary', 'LIKE', '%'.$summary.'%' );
        if(!empty($document_type))  $documents->where('document_type_id', 'LIKE', '%'.$document_type.'%' );
        if(!empty($search_tags))  $documents->where('search_tags', 'LIKE', '%'.$search_tags.'%' );
        if(!empty($date_from))  $documents->whereDate('created_at', '>=', $date_from );
        if(!empty($date_to))  $documents->whereDate('created_at', '<=', $date_to );
        
        $documents = $documents->get();
         
        if(!empty($inhalt)) $variants = EditorVariant::where('inhalt', 'LIKE', '%'.$inhalt.'%')->get();
        
        foreach ($documents as $document) if(!in_array($document, $results)) array_push($results, $document);
        
        if(count($variants)){
            foreach ($variants as $variant){
                if(!in_array($variant->document, $results)) 
                    array_push($results, $variant->document);
            }
        } else {
            $variants = EditorVariant::all();
        }
        
        $request->flash();
        
        if($emptySearch) $results = null;
        
        return view('suche.erweitert', compact('results', 'variants', 'documentTypes'));
    }

    /**
     * Return search results for the phone list users/mandants.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchPhoneList(Request $request)
    {
        // dd( $request->all() );
        $mandants = $this->search->phonelistSearch($request);
        // dd($mandants);
        return view('telefonliste.index', compact('mandants') );
      // return redirect()->action('TelephoneListController@index', array('array'=>$results) );
        // return redirect('telefonliste');
    }
    
}
