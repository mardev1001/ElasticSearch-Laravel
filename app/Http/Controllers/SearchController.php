<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Repositories\SearchRepository;
use App\Http\Repositories\DocumentRepository;

use App\User;
use App\Document;
use App\DocumentType;
use App\EditorVariant;
use App\MandantUser;
use App\WikiPage;

class SearchController extends Controller
{
     /**
     * Class constructor
     *
     */
    public function __construct(SearchRepository $searchRepo, DocumentRepository $docRepo)
    {
        $this->search =  $searchRepo;
        $this->document =  $docRepo;
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
        $resultsWiki = array();
        $variants = array();
        $documentTypes = DocumentType::all();
        
        
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
        
        if($request->has('parameter')){
            $parameter = $request->input('parameter');
            
            $documents = Document::where(function($query) use ($parameter) {
                                    
                                    $qmr = trim(str_ireplace('QMR', '', $parameter));
                                    $qmrNumber = (int) preg_replace("/[^0-9]+/", "", $qmr);
                                    $qmrString = preg_replace("/[^a-zA-Z]+/", "", $qmr);
                                    // dd($qmrNumber);
                                    $query->where('name', 'LIKE', '%'.$parameter.'%' )
                                    ->orWhere('qmr_number', $qmrNumber)
                                    ->orWhere('additional_letter', $qmrString)
                                    ->orWhere('search_tags', 'LIKE', '%'.$parameter.'%' )
                                    ->orWhere('summary', 'LIKE', '%'.$parameter.'%' )
                                    ->orWhere('betreff', 'LIKE', '%'.$parameter.'%' );
                                })
                                ->where('document_status_id', 3)
                                // ->where('document_status_id','!=', 5)
                                ->where('active', 1)
                                // ->where('deleted_at', null)
                                ->get();
            // dd($documents);
            $variants = EditorVariant::where('inhalt', 'LIKE', '%'.$parameter.'%')->get();
            
            foreach ($documents as $document) if(!in_array($document, $results)) array_push($results, $document);
            
            if(count($variants)){
                foreach ($variants as $variant){
                    if(!in_array($variant->document, $results)){
                        if($variant->document->document_status_id == 3)
                            array_push($results, $variant->document);
                    }
                }
            } else {
                $variants = EditorVariant::all();
            }
            
            // dd($results);
        }
        
        return view('suche.erweitert', compact('parameter','results','resultsWiki','variants','documentTypes','mandantUsers'));
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
        
        foreach ($inputs as $key=>$input){
            if($key != 'wiki' && $key != 'inhalt'){
                if(!empty($input)){
                    $emptySearch = false;
                }
            }
        }
        
        $results = array();
        $resultsWiki = array();
        $variants = array();
        $documentTypes = DocumentType::all();
        
        $mandantUsers = array();
        foreach(MandantUser::all() as $mandantUser){
            foreach(Auth::user()->mandantUsers as $loggedUserMandant){
                if($loggedUserMandant->mandant_id == $mandantUser->mandant_id){
                    if(!in_array($mandantUser, $mandantUsers))
                        array_push($mandantUsers, $mandantUser);
                }
            }
        }
        
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
        
        // dd($request->all());
        
        $documents = Document::where('id', '>', 0)
        ->where('document_status_id', 3)
        // ->where('document_status_id','!=', 5)
        ->where('active', 1);
        
        if($history) $documents = $documents->where('deleted_at', '!=', null);
        else $documents = $documents->where('deleted_at', null);
        
        // // QMR search
        // if(!empty($name)){
        //     dd($name);
        // }
        
        if(!empty($name)){ 
            $documents->where('name', 'LIKE', '%'.$name.'%')
                ->orWhere(function($query) use ($name){
                // QMR search
                $qmr = trim(str_ireplace('QMR', '', $name));
                $qmrNumber = (int) preg_replace("/[^0-9]+/", "", $qmr);
                $qmrString = preg_replace("/[^a-zA-Z]+/", "", $qmr);
                $query->where('qmr_number', $qmrNumber)->orWhere('additional_letter', $qmrString)->where('document_status_id', 3);
            });
        }
        if(!empty($betreff))  $documents->where('betreff', 'LIKE', '%'.$betreff.'%' );
        if(!empty($summary))  $documents->where('summary', 'LIKE', '%'.$summary.'%' );
        if(!empty($document_type))  $documents->where('document_type_id', 'LIKE', '%'.$document_type.'%' );
        if(!empty($search_tags))  $documents->where('search_tags', 'LIKE', '%'.$search_tags.'%' );
        if(!empty($date_from))  $documents->whereDate('created_at', '>=', $date_from );
        if(!empty($date_to))  $documents->whereDate('created_at', '<=', $date_to );
        if(!empty($user_id))  $documents->where('user_id', $user_id );
        
        $documents = $documents->get();
         
        if(!empty($inhalt)) $variants = EditorVariant::where('inhalt', 'LIKE', '%'.$inhalt.'%')->get();
        
        foreach ($documents as $document) if(!in_array($document, $results)) array_push($results, $document);
        
        if($emptySearch) $results = array();
        
        if(count($variants)){
            foreach ($variants as $variant){
                if(!in_array($variant->document, $results)) 
                    array_push($results, $variant->document);
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
        }
        
        // if($emptySearch) $results = null;
        if(empty($name) && empty($inhalt)) $resultsWiki = null;
        
        return view('suche.erweitert', compact('results', 'resultsWiki', 'variants', 'documentTypes', 'mandantUsers'));
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
        foreach($mandants as $k =>$mandant){
                
                $userArr = array();
                $testuserArr = array();
                $mandantUsers = $mandant->users;
                
                // if($request->has('deletedUsers') )
                //     $mandantUsers = $mandant->usersWithTrashed;
                
                foreach($mandantUsers as $k2 => $mUser){
                    foreach($mUser->mandantRoles as $mr){
                         $testuserArr[] = $mr->role->name;
                         if( $mr->role->phone_role == 1  || $mr->role->id == 23 || $mr->role->id == 21)
                            $userArr[] = $mandantUsers[$k2]->id;
                    }
                    
                    if( count($userArr) < 1){
                        foreach($mUser->mandantRoles as $mr){
                            if( $mr->role->id == 23 )// Lohn
                                $userArr[] = $mandantUsers[$k2]->id;
                        }   
                    }
                    
                    if( count($userArr) < 1){
                        foreach($mUser->mandantRoles as $mr){
                            if( $mr->role->name == 'Geschäftsführer' || $mr->role->name == 'Qualitätsmanager' || $mr->role->name == 'Rechntabteilung' 
                            ||  $mr->role->phone_role == true ||  $mr->role->phone_role == 1 )
                                $userArr[] = $mandantUsers[$k2]->id;
                        }   
                    }
                    
                }//end second foreach
                
                $userQuery = User::whereIn('id',$userArr);
                
                     if( $request->has('parameter') )
                        $userQuery->where('first_name',$request->get('parameter') )->orWhere('last_name',$request->get('parameter') );
                    
                $mandant->usersInMandants = $mandantUsers->whereIn('id',$userArr);
                   if($request->has('deletedUsers') )
                       $userQuery = $mandant->usersWithTrashed->whereIn('id',$userArr);   
                       
                     $userQuery = $userQuery->get();
                     
                     if( count( $userQuery ) )
                         $mandant->usersInMandants = $userQuery;
                     else
                      $mandant->usersInMandants = $mandant->usersWithTrashed->whereIn('id',$userArr);   
              // if($request->has('deletedUsers') )
               //  dd($mandant->usersInMandants);
                //  dd($userArr);
            }
        $search = true;
        $request->flash();
        return view('telefonliste.index', compact('mandants','search') ) ;
      // return redirect()->action('TelephoneListController@index', array('array'=>$results) );
        // return redirect('telefonliste');
    }
    
}
