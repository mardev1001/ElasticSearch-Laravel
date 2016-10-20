<?php

namespace App\Http\Controllers;

use Auth;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Repositories\SearchRepository;
use App\Http\Repositories\DocumentRepository;
use App\Http\Repositories\UtilityRepository;

use App\Document;
use App\DocumentType;
use App\EditorVariant;
use App\User;
use App\Role;
use App\Mandant;
use App\MandantUser;
use App\MandantUserRole;
use App\InternalMandantUser;
use App\WikiPage;

use App\Helpers\ViewHelper;

class SearchController extends Controller
{
     /**
     * Class constructor
     *
     */
    public function __construct(SearchRepository $searchRepo, DocumentRepository $docRepo, UtilityRepository $utilRepo )
    {
        $this->search =  $searchRepo;
        $this->document =  $docRepo;
        $this->utility =  $utilRepo;
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
        $searchQmrIso = false;
        $results = array();
        $resultsWiki = array();
        $variants = array();
        $searchResultsPaginated = array();
        // $documentTypes = DocumentType::all();
        $documentTypes = DocumentType::where('active', 1)->where('visible_navigation', 1)->get();
        
        
        // Fill the $mandantUsers array with users that share the same mandant as the logged in user
        $mandantUsers = array();
        foreach(MandantUser::all() as $mandantUser){
            foreach(Auth::user()->mandantUsers as $loggedUserMandant){
                if($loggedUserMandant->mandant_id == $mandantUser->mandant_id){
                    if(!in_array($mandantUser, $mandantUsers))
                        array_push($mandantUsers, $mandantUser);
                }
            }
        }
        // dd($mandantUsers);
        
        if($request->get('sort') == 'asc') $sort = 'asc';
        else $sort = 'desc';
        
        if($request->has('parameter')){
            
            $parameter = $request->input('parameter');
            
            //join('document_types', 'documents.document_type_id', '=', 'document_types.id')
            $documents = Document::where('document_status_id', 3)->where('active', 1);
            // $documents = Document::join('editor_variants', 'documents.id', '=', 'editor_variants.document_id')
                // ->where('document_status_id', 3)->where('active', 1)->groupBy('documents.id');
            
            // Check for occurence of "QMR" string, search for QMR documents if found
            if( stripos($parameter, 'QMR') !== false ){
                $searchQmrIso = true;
                $qmr = trim(str_ireplace('QMR', '', $parameter));
                $qmrNumber = (int) preg_replace("/[^0-9]+/", "", $qmr);
                $qmrString = preg_replace("/[^a-zA-Z]+/", "", $qmr);
                // dd(!empty($qmrString));
                
                $documents = $documents->where(function($query) use ($qmrNumber, $qmrString) {
                    if($qmrNumber) $query = $query->where('qmr_number', 'LIKE', $qmrNumber);
                    if(!empty($qmrString)) $query = $query->where('additional_letter', 'LIKE', '%'.$qmrString.'%');
                });
                
                $documents->where('document_type_id', 3);
                // $results = $documents->where('document_type_id', 3)->get();
                // $results = $this->filterByVisibility(collect($results));
                
                // $documents = $documents->where('document_type_id', 3);
                // $documents = $this->filterByVisibility($documents->get());
                // $searchResultsPaginated = Document::whereIn('id', array_pluck($documents, 'id'))->paginate(25, ['*', 'documents.id as id'],'suchergebnisse');
                // $searchResultsTree = $this->document->generateTreeview($searchResultsPaginated, array('pageSearch' => true));
                
                // $resultIds = array_pluck($results, 'id');
            
                // if($sort == 'asc')
                //     $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'asc')->get();
                // else
                //     $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'desc')->get();
                
                // return view('suche.erweitert', compact('parameter','results','resultsWiki','variants','documentTypes','mandantUsers'));
                // return view('suche.erweitert', compact('parameter','results','resultsWiki','variants','documentTypes','mandantUsers', 'searchResultsPaginated', 'searchResultsTree'));
                
            } elseif( stripos($parameter, 'ISO') !== false ){
                $searchQmrIso = true;
                $iso = trim(str_ireplace('ISO', '', $parameter));
                $isoNumber = (int) preg_replace("/[^0-9]+/", "", $iso);
                $isoString = preg_replace("/[^a-zA-Z]+/", "", $iso);
                // dd($isoNumber);
                
                $documents = $documents->where(function($query) use ($isoNumber, $isoString) {
                    if($isoNumber) $query = $query->where('iso_category_number', 'LIKE', $isoNumber);
                    if(!empty($isoString)) $query = $query->where('additional_letter', 'LIKE', '%'.$isoString.'%');
                });
                
                $documents->where('document_type_id', 4);
                // $results = $documents->where('document_type_id', 4)->get();
                // $results = $this->filterByVisibility(collect($results));
                
                // $documents = $documents->where('document_type_id', 4);
                // $documents = $this->filterByVisibility($documents->get());
                // $searchResultsPaginated = Document::whereIn('id', array_pluck($documents, 'id'))->paginate(25, ['*', 'documents.id as id'],'suchergebnisse');
                // $searchResultsTree = $this->document->generateTreeview($searchResultsPaginated, array('pageSearch' => true));
                
                // $resultIds = array_pluck($results, 'id');
            
                // if($sort == 'asc')
                //     $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'asc')->get();
                // else
                //     $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'desc')->get();
                
                // return view('suche.erweitert', compact('parameter','results','resultsWiki','variants','documentTypes','mandantUsers'));
                // return view('suche.erweitert', compact('parameter','results','resultsWiki','variants','documentTypes','mandantUsers', 'searchResultsPaginated', 'searchResultsTree'));
                
            } else {
                // Search for other parameters
                $documents = $documents->where(function($query) use ($parameter) {
                    $query->where('name_long', 'LIKE', '%'.$parameter.'%' )
                    ->orWhere('search_tags', 'LIKE', '%'.$parameter.'%' )
                    ->orWhere('summary', 'LIKE', '%'.$parameter.'%' )
                    ->orWhere('betreff', 'LIKE', '%'.$parameter.'%' );
                    
                    // ->orWhere('betreff', 'LIKE', '%'.$parameter.'%' )
                    // ->orWhere('inhalt', 'LIKE', '%'.$parameter.'%' );
                });
                
            }       

            // $results = $this->filterByVisibility($documents->get());
            // $documents = $this->filterByVisibility($documents->get());
            // $searchResultsPaginated = Document::whereIn('id', array_pluck($documents, 'id'))->paginate(25, ['*', 'documents.id as id'],'suchergebnisse');
            // $searchResultsTree = $this->document->generateTreeview($searchResultsPaginated, array('pageSearch' => true));
            
            // dd($searchResultsPaginated->getCollection());
            
            $documents = $documents->get();
            // dd($documents);
            
            // $variantsQuery = EditorVariant::where('inhalt', 'LIKE', '%'.$parameter.'%')->get();
            $variantsQuery = EditorVariant::whereNotNull('inhalt')->get();
            foreach ($variantsQuery as $tmp) {
                // Filter out images to only get the content
                $filteredVariant = preg_replace("/<img[^>]+\>/i", "", $tmp->inhalt);
                // Check if the search string is contained withing variants content
                if(stripos($filteredVariant, $parameter) !== false)
                    $variants[] = $tmp;
            }
            // dd($variants);
            
            foreach ($documents as $document) if(!in_array($document, $results)) array_push($results, $document);
            
            if(count($variants)){
                foreach ($variants as $variant){
                    if(isset($variant->document)){
                        if(!in_array($variant->document, $results)){
                            if($variant->document->document_status_id == 3)
                                array_push($results, $variant->document);
                        }
                    }
                }
            } 
            // else {
            //     $variants = EditorVariant::all();
            // }
            
            // TODO: pluck ids and sort from db
            
            // dd($results);
            // sort by collection $sort
            // $results = collect($results);
            
            // $results = $results->sortBy('date_published');
            
                
            $results = $this->filterByVisibility(collect($results));
            $resultIds = array_pluck($results, 'id');
            
            if($sort == 'asc')
                $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'asc')->get();
            else
                $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'desc')->get();
            
            // Sort by results
            // $results = array_values(array_sort($results, function ($value) {
            //     return $value['date_published'];
            // }));
            //$sorted = $collection->sortBy('price');  sortByDesc();
        }
        
        return view('suche.erweitert', compact('parameter','results','resultsWiki','variants','documentTypes','mandantUsers'));
        // return view('suche.erweitert', compact('parameter', 'resultsWiki', 'documentTypes','mandantUsers', 'searchResultsTree', 'searchResultsPaginated'));
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
        $searchQmrIso = false;
        $emptySearch = true;
        $emptyDocs = true;
        $inputs = $request->all();
        $results = array();
        $resultsWiki = array();
        $variants = array();
        $searchResultsPaginated = array();
        // $documentTypes = DocumentType::all();
        $documentTypes = DocumentType::where('active', 1)->where('visible_navigation', 1)->get();
        
        if($request->get('sort') == 'asc') $sort = 'asc';
        else $sort = 'desc';
        
        foreach ($inputs as $key=>$input){
            // if($key != 'wiki' && $key != 'inhalt'){
            if($key != 'wiki' && $key != 'adv-search'){
                if(!empty($input)){
                    $emptySearch = false;
                }
            }
            
            if($key != 'inhalt' && $key != 'wiki' && $key != 'adv-search'){
                if(!empty($input)){
                    $emptyDocs = false;
                }
            }
        }
        
        // dd($emptyDocs);
        
        $mandantUsers = array();
        foreach(MandantUser::all() as $mandantUser){
            foreach(Auth::user()->mandantUsers as $loggedUserMandant){
                if($loggedUserMandant->mandant_id == $mandantUser->mandant_id){
                    if(!in_array($mandantUser, $mandantUsers))
                        array_push($mandantUsers, $mandantUser);
                }
            }
        }
        
        $parameter = $request->input('parameter');
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
        $history = $request->has('history');
        $user_id = $request->input('user_id');
        $qmr_number = $request->input('qmr_number');
        $iso_category_number = $request->input('iso_category_number');
        $additional_letter = $request->input('additional_letter');
        
        // dd($request->all());
        
        // DB::enableQueryLog();
        
        $documents = Document::where('document_status_id', 3)->where('active', 1);
        
        // $documents = Document::where('id', '>', 0)
        // // $documents = Document::join('editor_variants', 'documents.id', '=', 'editor_variants.document_id')
        // ->where('documents.id', '>', 0)
        // // ->where('document_status_id','!=', 5)
        // ->where('active', 1);
        // // ->groupBy('documents.id');
        
        // Add-in parameter for use inside advanced search
        if(!empty($parameter)){
            
            // Check for occurence of "QMR" string, search for QMR documents if found
            if( stripos($parameter, 'QMR') !== false ){
                $searchQmrIso = true;
                $qmr = trim(str_ireplace('QMR', '', $parameter));
                $qmrNumber = (int) preg_replace("/[^0-9]+/", "", $qmr);
                $qmrString = preg_replace("/[^a-zA-Z]+/", "", $qmr);
                
                $documents = $documents->where(function($query) use ($qmrNumber, $qmrString) {
                    if($qmrNumber) $query = $query->where('qmr_number', 'LIKE', $qmrNumber);
                    if(!empty($qmrString)) $query = $query->where('additional_letter', 'LIKE', '%'.$qmrString.'%');
                });
                
                $documents->where('document_type_id', 3);
                // $results = $documents->where('document_type_id', 3)->get();
                // $results = $this->filterByVisibility(collect($results));
                
                // $resultIds = array_pluck($results, 'id');
                // if($sort == 'asc') $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'asc')->get();
                // else $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'desc')->get();
                
                // $request->flash();
                // return view('suche.erweitert', compact('parameter','results','resultsWiki','variants','documentTypes','mandantUsers'));
                
            } elseif( stripos($parameter, 'ISO') !== false ){
                $searchQmrIso = true;
                $iso = trim(str_ireplace('ISO', '', $parameter));
                $isoNumber = (int) preg_replace("/[^0-9]+/", "", $iso);
                $isoString = preg_replace("/[^a-zA-Z]+/", "", $iso);
                
                $documents = $documents->where(function($query) use ($isoNumber, $isoString) {
                    if($isoNumber) $query = $query->where('iso_category_number', 'LIKE', $isoNumber);
                    if(!empty($isoString)) $query = $query->where('additional_letter', 'LIKE', '%'.$isoString.'%');
                });
                
                $documents->where('document_type_id', 4);
                // $results = $documents->where('document_type_id', 4)->get();
                // $results = $this->filterByVisibility(collect($results));
                
                // $resultIds = array_pluck($results, 'id');
                // if($sort == 'asc') $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'asc')->get();
                // else $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'desc')->get();
                
                // $request->flash();
                // return view('suche.erweitert', compact('parameter','results','resultsWiki','variants','documentTypes','mandantUsers'));
                
            } else {
                
                $documents->where(function($query) use ($parameter) {
                    $query->where('name_long', 'LIKE', '%'.$parameter.'%' )
                        ->orWhere('search_tags', 'LIKE', '%'.$parameter.'%' )
                        ->orWhere('betreff', 'LIKE', '%'.$parameter.'%')
                        ->orWhere('summary', 'LIKE', '%'.$parameter.'%');
                });
                
            }
            
        }
        
        
        
        if($history) $documents = $documents->where('documents.deleted_at', '!=', null);
        else $documents = $documents->where('documents.deleted_at', null);
        
        // dd($documents->get());
        
        if(!empty($name)) $documents->where('name_long', 'LIKE', '%'.$name.'%' );
        
        if(!empty($search_tags)) $documents->where('search_tags', 'LIKE', '%'.$search_tags.'%' );
        
        if(!empty($betreff)) $documents->where('betreff', 'LIKE', '%'.$betreff.'%');
        
        if(!empty($summary)) $documents->where('summary', 'LIKE', '%'.$summary.'%');
        
        if(!empty($document_type))  {
            if($document_type == 3){
                if(!empty($qmr_number))  $documents->where('qmr_number', $qmr_number );
                if(!empty($additional_letter))  $documents->where('additional_letter', 'LIKE', '%'.$additional_letter.'%' );
            } elseif($document_type == 4){
                if(!empty($iso_category_number))  $documents->where('iso_category_number', $iso_category_number );
                if(!empty($additional_letter))  $documents->where('additional_letter', 'LIKE', '%'.$additional_letter.'%' );
            }
            // $documents->where('document_type_id', 'LIKE', '%'.$document_type.'%' );
            $documents->where('document_type_id', $document_type );
        }
        
        
        // if(!empty($date_from))  $documents->whereDate('documents.created_at', '>=', $date_from );
        // if(!empty($date_to))  $documents->whereDate('documents.created_at', '<=', $date_to );
        
        if(!empty($date_from))  $documents->whereDate('documents.date_published', '>=', $date_from );
        if(!empty($date_to))  $documents->whereDate('documents.date_published', '<=', $date_to );
        if(!empty($user_id))  $documents->where('owner_user_id', $user_id );
        
        // $documents = $this->filterByVisibility($documents->get());
        // $searchResultsPaginated = Document::whereIn('id', array_pluck($documents, 'id'))->paginate(25, ['*', 'documents.id as id'], 'suchergebnisse');
        // $searchResultsTree = $this->document->generateTreeview($searchResultsPaginated, array('pageSearch' => true));
        
        $documents = $documents->get();
        
        if($emptyDocs) $documents = array();

        // exit();
        // dd($documents);
        // dd(DB::getQueryLog());
        // dd($documents->toSql());
         
        // if(!empty($inhalt) || !empty($parameter)) $variants = EditorVariant::where('inhalt', 'LIKE', '%'.$inhalt.'%')->get();
        // if(!empty($inhalt) || !empty($parameter)) {
        //     $variants = EditorVariant::where('inhalt', 'LIKE', '%'.$inhalt.'%')
        //         ->orWhere('inhalt', 'LIKE', '%'.$parameter.'%')->get();
        // }
        
        // $variants = EditorVariant::where('id', '>', 0);
        // if(!empty($parameter)) $variants->where('inhalt', 'LIKE', '%'.$parameter.'%'); 
        // if(!empty($inhalt)) $variants->where('inhalt', 'LIKE', '%'.$inhalt.'%');
        // $variants = $variants->get();
        
        $variantsQuery = EditorVariant::whereNotNull('inhalt')->get();
        foreach ($variantsQuery as $tmp) {
            // Filter out images to only get the content
            $filteredVariant = preg_replace("/<img[^>]+\>/i", "", $tmp->inhalt);
            
            // Check if the search string is contained withing variants content
            if(!empty($parameter)) {
                if(stripos($filteredVariant, $parameter) !== false)
                    $variants[] = $tmp;
            }
            if(!empty($inhalt)) {
                if(stripos($filteredVariant, $inhalt) !== false)
                    $variants[] = $tmp;
            }
        }
        
        // dd($variants);
        
        if(empty($parameter) && empty($inhalt)) $variants = array();
        
        foreach ($documents as $document) if(!in_array($document, $results)) array_push($results, $document);
        
        if(count($variants)){
            foreach ($variants as $variant){
                if(isset($variant->document)){
                    if(!in_array($variant->document, $results)){
                        if($variant->document->document_status_id == 3){
                            if(!empty($document_type)){
                                if($variant->document->document_type_id == $document_type)
                                    array_push($results, $variant->document);
                            } else array_push($results, $variant->document);
                        }
                    }
                }
            }
        } 
        // else {
        //     $variants = EditorVariant::all();
        // }
        // dd($documents);
        $request->flash();
        
        if($wiki){
            // search for $name, $inhalt
            $resultsWiki = WikiPage::where('id', '>', 0)->where('status_id', 2)->where('active', 1);
            if(!empty($name)) $resultsWiki = $resultsWiki->where('name', 'LIKE', '%'. $name. '%');
            if(!empty($inhalt)) $resultsWiki = $resultsWiki->where('content', 'LIKE', '%'. $inhalt. '%');
            
            $resultsWiki = $resultsWiki->get();
            
            // $resultsWikiPagination = $resultsWiki->paginate(25, ['*'], 'suchergebnisse-wiki');
            // $resultsWikiTree = $this->document->generateWikiTreeview($resultsWikiPagination, ['pageSearch' => true]);
            // $resultsWiki = array();
            
        }
        
        // dd($results);
        $results = $this->filterByVisibility(collect($results));
        $resultIds = array_pluck($results, 'id');
    
        if($sort == 'asc')
            $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'asc')->get();
        else
            $results = Document::whereIn('id', $resultIds)->orderBy('date_published', 'desc')->get();
        
        // if($emptySearch) $searchResultsPaginated = array();
        if($emptySearch) $results = array();
        if(empty($name) && empty($inhalt)) $resultsWiki = null;
        
        return view('suche.erweitert', compact('results', 'parameter', 'resultsWiki', 'variants', 'documentTypes', 'mandantUsers'));
        // return view('suche.erweitert', compact('results', 'resultsWiki', 'resultsWikiPagination', 'resultsWikiTree', 'variants', 'documentTypes', 'mandantUsers', 'searchResultsPaginated', 'searchResultsTree'));
    }

    /**
     * Return search results for the phone list users/mandants.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function searchPhoneList(Request $request){
        if(!$request->has('search') || $request->method() == "GET")
            return redirect('telefonliste');
            
        $visible = $this->utility->getPhonelistSettings();
            
        $partner = false;
        $search = true;
        $searchParameter = $request->get('search');
        $roles = Role::all();
        $loggedUserMandant = MandantUser::where('user_id', Auth::user()->id)->first()->mandant;
        // dd($loggedUserMandant);
        
        // Get searched mandants
        $mandants = Mandant::where(function ($query) use($searchParameter) {
            $query-> where('name','LIKE', '%'. $searchParameter .'%')
        ->orWhere('kurzname','LIKE', '%'. $searchParameter .'%')
        ->orWhere('mandant_number','LIKE', '%'. $searchParameter .'%');
        }); 
        
        
        if(Auth::user()->id == 1 || $loggedUserMandant->id == 1 || $loggedUserMandant->rights_admin == 1)
            $mandants = $mandants->where('active', 1)->orderBy('mandant_number')->get();
        else{
            $partner = true;
            $mandants = $mandants->where('active', 1)->where('rights_admin', 1)->orderBy('mandant_number')->get();
        }

        // dd($loggedUserMandant);
        if(!$mandants->contains($loggedUserMandant) && (
            (stripos($loggedUserMandant->name, $searchParameter) !== false) ||
            (stripos($loggedUserMandant->kurzname, $searchParameter) !== false) ||
            (stripos($loggedUserMandant->mandant_number, $searchParameter) !== false )) ){
                $mandants->prepend($loggedUserMandant);
        }
        
        // Sort by Mandant No.
        $mandants = array_values(array_sort($mandants, function ($value) {
            return $value['mandant_number'];
        }));
        // dd($mandants);
        
        // Get users for searched mandants
        foreach($mandants as $k => $mandant){
            
            $userArr = array();
            $usersInternal = array();
            
            // Check if the logged user is in the current mandant
            $localUser = MandantUser::where('mandant_id', $mandant->id)->where('user_id', Auth::user()->id)->first();
            
            // Get all InternalMandantUsers
            // $internalMandantUsers = InternalMandantUser::where('mandant_id', $mandant->id)->get();
            $internalMandantUsers = InternalMandantUser::where('mandant_id', $loggedUserMandant->id)
                ->where('mandant_id_edit', $mandant->id)->get();
            foreach ($internalMandantUsers as $user)
                $usersInternal[] = $user;
    
            foreach($mandant->users as $k2 => $mUser){
                
                foreach($mUser->mandantRoles as $mr){
                    if($mUser->active){
                        // Check for phone roles
                        if( $mr->role->phone_role || $mr->role->mandant_role ) {
                            $internalRole = InternalMandantUser::where('role_id', $mr->role->id)->where('mandant_id', $mandant->id)->first();
                            if(!count($internalRole)){
                                $userArr[] = $mandant->users[$k2]->id;
                            }
                        }
                
                    }
                }
            } //end second foreach
            
            $mandant->usersInternal = $usersInternal;
            $mandant->usersInMandants = $mandant->users->whereIn('id', $userArr);
        }
        
        // Get mandants for searched users
        // if(Auth::user()->id == 1 || $loggedUserMandant->id == 1 || $loggedUserMandant->rights_admin == 1)
        if($partner)
            $mandantsSearch = Mandant::where('active', 1)->where('rights_admin', 1)->orderBy('mandant_number')->get();
        else
            $mandantsSearch = Mandant::where('active', 1)->orderBy('mandant_number')->get();
            
        $myMandantSearch = MandantUser::where('user_id', Auth::user()->id)->first()->mandant;
        if(!$mandantsSearch->contains($myMandantSearch))
            $mandantsSearch->prepend($myMandantSearch);
        
        $users = User::where(function ($query) use($searchParameter) {
            $query->where('first_name', 'LIKE', '%'. $searchParameter .'%')
        ->orWhere('last_name', 'LIKE', '%'. $searchParameter .'%');
        // ->orWhere('short_name', 'LIKE', '%'. $searchParameter .'%');
        });
        //dd( \DB::getQueryLog() );
        
        $usersInMandants = array();
        $usersInternal = array();
        $usersInMandantsInternal = array();

        // Get searched users    
        foreach($mandantsSearch as $k => $mandant){
            
            // $internalMandantUsers = InternalMandantUser::where('mandant_id', $mandant->id)->get();
            $internalMandantUsers = InternalMandantUser::where('mandant_id', $loggedUserMandant->id)
                ->where('mandant_id_edit', $mandant->id)->get();
            foreach ($internalMandantUsers as $user)
                $usersInMandantsInternal[] = $user;
            
            // dd($mandant->users);
            foreach($mandant->users as $k2 => $mUser){
                foreach($mUser->mandantRoles as $mr){
                    if($mUser->active){
                        // Check for phone roles
                        $internalRole = InternalMandantUser::where('role_id', $mr->role->id)->where('mandant_id', $mandant->id)->first();
                        if(!count($internalRole)){
                            // if( $mr->role->phone_role && !in_array($mandant->users[$k2]->id, $usersInMandants) )
                                 $usersInMandants[] = $mandant->users[$k2]->id;
                        }
                    }
                }
            }
        }
        
        // Add internal users if they satisfy search criteria
        $tmpUsrs = User::where(function ($query) use($searchParameter) {
            $query->where('first_name', 'LIKE', '%'. $searchParameter .'%')
            ->orWhere('last_name', 'LIKE', '%'. $searchParameter .'%');
        });
        
        foreach ($usersInMandantsInternal as $umi) {
            if(count($tmpUsrs->where('id', $umi->user_id)->get()))
                $usersInternal[] = $umi;
        }
        
        $users = $users->whereIn('id', $usersInMandants)->get();
        
        // dd($usersInternal);
        
        return view('telefonliste.index', compact('search', 'partner','searchParameter', 'mandants', 'users', 'usersInternal', 'roles', 'visible') );
    }
    
    // Function to filter out documents by permissions
    public function filterByVisibility($documents){
        
        foreach($documents as $key => $value){
            if(!ViewHelper::documentVariantPermission($value)->permissionExists)
                // dd($key);
                $documents->forget($key);
        }
        
        return $documents;
    }
    
}
