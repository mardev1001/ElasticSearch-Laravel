<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Auth;
use App\Document;
use App\DocumentType;
use App\PublishedDocument;
use App\FavoriteDocument;
use App\Http\Repositories\DocumentRepository;

class FavoritesController extends Controller
{
    
    public function __construct(DocumentRepository $docRepo){
        $this->favorites =  $docRepo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorites = $favoriteDocuments = $favoritesTreeview = $favoritesPaginated = array();
        $favoriteDocuments = FavoriteDocument::where('user_id', Auth::user()->id)->get();
        $documentTypes = DocumentType::all();
        
        $favoritesAll = array();
        // dd(array_pluck($documentTypes, array('id', 'name')));
        
        foreach ($documentTypes as $docType) {
            
            $favsArray = array();
            $favsTmp = array();
            
            $favsArray['document_type_id'] = $docType->id;
            $favsArray['document_type_name'] = $docType->name;
    
            foreach($favoriteDocuments as $fav){
                $published = PublishedDocument::where('document_group_id', $fav->document_group_id)->orderBy('id', 'desc')->first();
                if(isset($published->document)){
                    if($published->document->document_type_id == $docType->id)
                        array_push($favsTmp, $published->document);
                }
            }
    
            $favoritesPaginated = Document::whereIn('id', array_pluck($favsTmp, 'id'))->orderBy('name', 'asc')->paginate(10, ['*'], 'seite');
            $favoritesTreeview = $this->favorites->generateTreeview($favoritesPaginated, array('pageFavorites' => true, 'showDelete' => true, ));
            
            $favsArray['favoritesPaginated'] = $favoritesPaginated;
            $favsArray['favoritesTreeview'] = $favoritesTreeview;
            
            array_push($favoritesAll, $favsArray);
        }
        
        // dd($favoritesAll);
        
        return view('favoriten.index', compact('favoritesAll'));
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
