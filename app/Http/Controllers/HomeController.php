<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use Auth;
use App\Document;
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
        $documentsNew = Document::whereNotIn('document_type_id', array('5'))->orderBy('id', 'desc')->paginate(10, ['*'], 'neue-dokumente');
        $documentsNewTree = $this->document->generateTreeview( $documentsNew );
        // $dokumenteNeu = $this->document->generateTreeview(Document::where(['document_status_id' => 3])->orderBy('id', 'desc')->get()); // Where last login < document create date
        $rundschreibenMy = Document::where(['user_id' => Auth::user()->id, 'document_type_id' => 3, 'document_status_id' => 3])->orderBy('id', 'desc')->paginate(10, ['*'], 'my-roundschrieben');
        $rundschreibenMyTree = $this->document->generateTreeview( $rundschreibenMy );
        
        $documentsMy = Document::where(['user_id' => Auth::user()->id, 'document_status_id' => 3])->orderBy('id', 'desc')->paginate(10, ['*'], 'my-dokumente');
        $documentsMyTree = $this->document->generateTreeview($documentsMy);
        
        $wikiEntries = '[{"text":"Wiki Eintrag-74","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]},{"text":"Wiki Eintrag-79","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]},{"text":"Wiki Eintrag-25","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]},{"text":"Wiki Eintrag-166","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]},{"text":"Wiki Eintrag-19","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]}]';
        
        $commentsNew = '[{"text":"Neuer Kommentar-135","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-51","tags":[0]}]},{"text":"Neuer Kommentar-95","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-51","tags":[0]}]},{"text":"Neuer Kommentar-38","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-51","tags":[0]}]}]';
        $commentsMy = '[{"text":"Mein Kommentar-84","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-41","tags":[0]}]},{"text":"Mein Kommentar-51","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-41","tags":[0]}]}]';
        
        return view('dashboard', compact('documentsNew','documentsNewTree', 'rundschreibenMy','rundschreibenMyTree', 'documentsMy','documentsMyTree', 'wikiEntries', 'commentsNew', 'commentsMy'));
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
}
