<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

use Auth;
use File;
use Mail;
use URL;

use App\Document;
use App\DocumentComment;
use App\DocumentCoauthor;
use App\Mandant;
use App\User;
use App\MandantUser;
use App\MandantUserRole;
use App\WikiPage;
use App\DocumentApproval;
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
     * Create a length aware custom paginator instance.
     *
     * @param  Collection  $items
     * @param  int  $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
     
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        
        /*
            if role Struktur admin view all ()
            maybe if document/Rundscriben verfasser
            else if Find all document where document  user_id,owner_user_id or coAuthor
        */
        // $this->getDocumentsUser()
        // $this->universalDocumentPermission($document, false, array());
        // $documentsNew = Document::whereNotIn('document_status_id', array(1,2,4,5,6))->where('is_attachment',0)->where('active',1)
        $documentsNew = Document::join('document_types', 'documents.document_type_id', '=', 'document_types.id')
        ->where('document_status_id', 3)->where('is_attachment',0)->where('documents.active',1)
        ->where('document_types.document_art', 0)
        ->orderBy('documents.id', 'desc')
        ->get(['*','document_types.name as docTypeName', 'documents.name as name', 
		'document_types.id as docTypeId', 'documents.id as id', 'documents.created_at as created_at']);
		
		$documentsNew = $this->document->getUserPermissionedDocuments($documentsNew,'neue-dokumente');
        $documentsNewTree = $this->document->generateTreeview($documentsNew, array('pageHome' => true, 'showAttachments' => true, 'showHistory' => true));
        
        $myRundCoauthor = DocumentCoauthor::where('user_id',Auth::user()->id)->pluck('document_id')->toArray();
        $rundschreibenMy = Document::join('document_types', 'documents.document_type_id', '=', 'document_types.id')
        ->where('owner_user_id', Auth::user()->id)
        // ->where('user_id', Auth::user()->id)
        ->orWhere(
            /* Neptun-275 */
            function($query) use ($myRundCoauthor) {
                $query->where('user_id', Auth::user()->id)
                      ->orWhere('owner_user_id', Auth::user()->id);
                    //   ->documentCoauthors('',);
                $query->orWhereIn('documents.id',$myRundCoauthor);
            }
        )
        ->where('document_type_id', '!=', 5)
        ->where('document_status_id', '!=', 5)
        //->where('document_status_id', '!=', 6) Task in Jira NEPTUN-275
        ->where('document_types.document_art', 0)
        ->where('documents.active',1)
        // ->where('document_status_id', 3)
        // ->get();
        ->orderBy('documents.id', 'desc')->paginate(10, ['*','document_types.name as docTypeName', 'documents.name as name', 
		'document_types.id as docTypeId', 'documents.id as id', 'documents.created_at as created_at'], 'meine-rundschrieben');
        // dd($rundschreibenMy);
        $rundschreibenMyTree = $this->document->generateTreeview( $rundschreibenMy, array('pageHome' => true, 'showHistory' => true,'myDocuments' => true));
        
        $documentsMy = Document::where('user_id', Auth::user()->id)
        ->where('document_status_id',3)->orderBy('id', 'desc')->paginate(10, ['*'], 'meine-dokumente');
        $documentsMyTree = $this->document->generateTreeview($documentsMy, array('pageHome' => true));
        
        $uid = Auth::user()->id;
        $approval = DocumentApproval::where('user_id',$uid)->where('approved', 0)->pluck('document_id')->toArray();
        
        $freigabeEntries = Document::join('document_types', 'documents.document_type_id', '=', 'document_types.id')
        ->whereIn('document_status_id', [2,6]) 
        ->where('document_types.document_art', 0)
        ->where(
            function($query) use ($approval) {
                $query->where('user_id', Auth::user()->id)
                      ->orWhere('owner_user_id', Auth::user()->id);
                    //   ->documentCoauthors('',);
                $query->orWhereIn('documents.id',$approval);
            }
        )
        ->where('documents.active',1)
        // ->orWhere(function ($query) use($approval) {
        //         $query->whereIn('documents.id',$approval);
        // })
        ->orderBy('documents.id', 'desc')
        ->paginate(10, ['*', 'documents.id as id', 'documents.created_at as created_at', 'documents.name as name' ],'freigabe-dokumente');
        
        $freigabeEntriesTree = $this->document->generateTreeview($freigabeEntries, array('pageHome' => true));
        
        $wikiEntries = $this->document->generateWikiTreeview(WikiPage::orderBy('created_at','DESC')->take(5)->get());
        // $wikiEntries = '[{"text":"Wiki Eintrag-74","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]},{"text":"Wiki Eintrag-79","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]},{"text":"Wiki Eintrag-25","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]},{"text":"Wiki Eintrag-166","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]},{"text":"Wiki Eintrag-19","tags":[2],"nodes":[{"text":"Lorem Ipsum-136","tags":[0]},{"text":"Lorem Ipsum-108","tags":[0]}]}]';
        
        // $commentsNew = '[{"text":"Neuer Kommentar-135","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-51","tags":[0]}]},{"text":"Neuer Kommentar-95","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-51","tags":[0]}]},{"text":"Neuer Kommentar-38","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-51","tags":[0]}]}]';
        // $commentsMy = '[{"text":"Mein Kommentar-84","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-41","tags":[0]}]},{"text":"Mein Kommentar-51","tags":[1],"nodes":[{"text":"Kommentar Text Lorem Ipsum Dolor Sit Amet-41","tags":[0]}]}]';
        
        $commentsNew = DocumentComment::where('id', '>', 0)->orderBy('created_at', 'desc')->take(10)->get();
        
        $commentVisibility = false;
        $uid = Auth::user()->id;  
        /* Freigabe user */
        $mandantUsers =  MandantUser::where('user_id',$uid)->get();
        foreach($mandantUsers as $mu){
            $userMandatRoles = MandantUserRole::where('mandant_user_id',$mu->id)->get();
            foreach($userMandatRoles as $umr){
                if($umr->role_id == 9 || $umr->role_id == 1)
                    $commentVisibility = true;
            }
        }
        /* End Freigabe user */
        $commentsMy = DocumentComment::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->take(10)->get();
        // dd($commentsMy);
        // dd(Auth::user()->id);
        // dd($commentVisibility);
        return view('dashboard', compact('documentsNew','documentsNewTree', 'rundschreibenMy','rundschreibenMyTree', 'freigabeEntries', 'freigabeEntriesTree', 
        'documentsMy','documentsMyTree', 'wikiEntries', 'commentsNew', 'commentsMy','commentVisibility'));
    }

    /**
     * Contact form
     * @param string $partOne
     * @param string $partTwo
     * @param string $subDir
     *
     * @return \Illuminate\Http\Response download
     */
    public function contact() 
    {
        //Dropdown: ALL Neptun - active - user - firstname lastname
        $data = array();
        $neptun = Mandant::find(1);
        $mandantUsers =  MandantUser::where('mandant_id',$neptun->id)->pluck('user_id')->toArray();
        $users = User::whereIn('id',$mandantUsers)->where('active',1)->get();
        
        return view('contact', compact(  'data', 'users' ) );
    }
    
    /**
     * Contact form
     * @param string $partOne
     * @param string $partTwo
     * @param string $subDir
     *
     * @return \Illuminate\Http\Response download
     */
    public function contactSend(Request $request) 
    {
        $uid = Auth::user()->id;
        $copy = false;
        if($request->has('copy') )
            $copy = true;
        $request = $request->all();
        $from = User::find($uid);
        $request['logo'] = asset('/img/logo-neptun-new.png');
        $request['from'] = $from;
        
        $template = view('email.contact' ,compact('request') )->render();
        $sent= Mail::send([], [], function ($message) use($template,$request,$from){
            // dd($request['to_user']);
            $uid = Auth::user()->id;
            $to = User::find($request['to_user']);
            $message->from(  $from->email, $from->first_name.' '.$from->last_name  )
            ->to( $to->email, $to->first_name.' '.$to->last_name )
            ->subject($request['subject'] )
            ->setBody($template, 'text/html');
        });
        if($copy == true){
            $request['copy'] = 'yes';
            $request['subject'] = 'E-Mail Kopie "'.$request['subject'].'"';
            $template = view('email.contact' ,compact('request') )->render();
            $sent= Mail::send([], [], function ($message) use($template,$request,$from){
                // dd($request['to_user']);
                $uid = Auth::user()->id;
                $to = User::find($request['to_user']);
                $message->from(  $from->email, $from->first_name.' '.$from->last_name  )
                ->to( $from->email, $from->first_name.' '.$from->last_name )
                ->subject($request['subject'] )
                ->setBody($template, 'text/html');
            });   
        }
        
        
        return redirect()->back()->with('message', 'Email wurde erfolgreich Versendet.');
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
