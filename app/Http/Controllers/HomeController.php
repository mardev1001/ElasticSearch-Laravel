<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use Auth;
use File;

use App\Document;
use App\DocumentComment;
use App\Http\Repositories\DocumentRepository;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DocumentRepository $docRepo)
    {
      $this->document = $docRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $documentsNew = Document::whereNotIn('document_status_id', array(1,4,5,6))->where('is_attachment',0)
        ->orderBy('id', 'desc')->paginate(10, ['*'], 'neue-dokumente');
        $documentsNewTree = $this->document->generateTreeview($documentsNew, array('pageHome' => true, 'showHistory' => true));
        
        $rundschreibenMy = Document::where(['user_id' => Auth::user()->id, 'document_type_id' => 2, 'document_status_id' => 3])
        ->orderBy('id', 'desc')->paginate(10, ['*'], 'meine-rundschrieben');
        $rundschreibenMyTree = $this->document->generateTreeview( $rundschreibenMy, array('pageHome' => true));
        
        $documentsMy = Document::where('user_id', Auth::user()->id)
        ->where('document_status_id',3)->orderBy('id', 'desc')->paginate(10, ['*'], 'meine-dokumente');
        $documentsMyTree = $this->document->generateTreeview($documentsMy, array('pageHome' => true));
        
        $freigabeEntries = Document::where('document_status_id', 6)->where(
            function($query){
                $query->where('user_id', Auth::user()->id)
                      ->orWhere('owner_user_id', Auth::user()->id);
                    //   ->documentCoauthors('',);
            }
        )->orderBy('id', 'desc')->paginate(10, ['*'], 'freigabe-dokumente');
        
        $freigabeEntriesTree = $this->document->generateTreeview($freigabeEntries, array('pageHome' => true));
        
        $wikiEntries = '[{"text":"Wiki Eintrag-74","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]},{"text":"Wiki Eintrag-79","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]},{"text":"Wiki Eintrag-25","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]},{"text":"Wiki Eintrag-166","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]},{"text":"Wiki Eintrag-19","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]}]';
        
        // $commentsNew = '[{"text":"Neuer Kommentar-135","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-51","tags":[0]}]},{"text":"Neuer Kommentar-95","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-51","tags":[0]}]},{"text":"Neuer Kommentar-38","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-51","tags":[0]}]}]';
        // $commentsMy = '[{"text":"Mein Kommentar-84","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-41","tags":[0]}]},{"text":"Mein Kommentar-51","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-41","tags":[0]}]}]';
        
        $commentsNew = DocumentComment::where('id', '>', 0)->orderBy('id', 'desc')->take(10)->get();
        $commentsMy = DocumentComment::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->take(10)->get();
        // dd($commentsNew);
        
        return view('dashboard', compact('documentsNew','documentsNewTree', 'rundschreibenMy','rundschreibenMyTree', 'freigabeEntries', 'freigabeEntriesTree', 'documentsMy','documentsMyTree', 'wikiEntries', 'commentsNew', 'commentsMy'));
    }

    /**
     * Download document
     * @param string $partOne
     * @param string $partTwo
     * @param string $subDir
     *
     * @return \Illuminate\Http\Response download
     */
    public function download($partOne,$partTwo,$subDir='documents') 
    {
        $file= public_path(). '/files/'.$subDir.'/'.$partOne.'/'.$partTwo;
        return response()->download($file);
    }
    
    /**
     * Open document (PDF)
     * @param string $partOne
     * @param string $partTwo
     * @param string $subDir
     *
     * @return \Illuminate\Http\Response download
     */
    public function open($partOne, $partTwo, $subDir='documents')
    {
        $file = File::get(public_path(). '/files/'.$subDir.'/'.$partOne.'/'.$partTwo);
        return response($file, 200)->header('Content-Type', 'application/pdf');
    }
}
