<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use Carbon\Carbon;
use App\Http\Requests;
use App\Helpers\ViewHelper;
use App\Http\Requests\DocumentRequest;
use App\Http\Repositories\SearchRepository;
use App\Http\Repositories\DocumentRepository;

use App\WikiPage;
use App\WikiPageStatus;
use App\WikiRole;
use App\WikiCategory;
use App\WikiCategoryUser;
use App\WikiPageHistory;
use App\User;

class WikiController extends Controller
{
     /**
     * Class constructor
     */
    public function __construct(SearchRepository $searchRepo, DocumentRepository $docRepo)
    {
        $this->middleware('wiki')->only('index', 'search', 'show');
        $this->middleware('wiki.editor')->except('index', 'search', 'show');
        $this->search = $searchRepo;
        $this->document = $docRepo;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if( ViewHelper::universalHasPermission( array(15,16) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
            
        $topCategories = WikiCategory::where('top_category',1)->get();
        
        $newestWikiEntriesPagination = WikiPage::where('status_id',2)->orderBy('created_at','DESC')->paginate(10, ['*'], 'neueste-beitraege');
        $newestWikiEntries = $this->document->generateWikiTreeview($newestWikiEntriesPagination);
        
        $myWikiPagesPagination = WikiPage::where('user_id', Auth::user()->id)->orderBy('created_at','DESC')->paginate(10, ['*'], 'meine-beitraege');
        $myWikiPages = $this->document->generateWikiTreeview($myWikiPagesPagination);
        
        return view('wiki.index', compact('topCategories','newestWikiEntries','newestWikiEntriesPagination','myWikiPages','myWikiPagesPagination'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if( ViewHelper::universalHasPermission( array(15,16) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
            
        $wikiStatuses = WikiPageStatus::all();
        $wikiRoles = WikiRole::all();
        $wikiCategories = WikiCategory::all();
        return view('formWrapper', compact('data','wikiCategories','wikiStatuses','wikiRoles') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //auto date
        //auto update
        //user_id
        // dd($request->all() );
        $wiki = WikiPage::create( $request->all() );
        
        $wikiStatuses = WikiPageStatus::all();
        $wikiRoles = WikiRole::all();
        $wikiCategories = WikiCategory::all();
        
        session()->flash('message',trans('wiki.wikiCreateSuccess'));
        return redirect('wiki/'.$wiki->id.'/edit' );
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if( ViewHelper::universalHasPermission( array(15,16) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
            
        $data = WikiPage::find($id);
        
        return view('wiki.show', compact('data') );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if( ViewHelper::universalHasPermission( array(15,16) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
            
        $data = WikiPage::find($id);
        $wikiStatuses = WikiPageStatus::all();
        $wikiRoles = WikiRole::all();
        $wikiCategories = WikiCategory::all();
        return view('formWrapper', compact('data','wikiCategories','wikiStatuses','wikiRoles') );
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
        // dd( $request->all() );
        $data = WikiPage::find($id);
        $data->fill( $request->all() )->save();
        session()->flash('message',trans('wiki.wikiEditSuccess'));
        return redirect()->back();
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function wikiActivation($id)
    {
        $wiki = WikiPage::find($id);
        if($wiki->active == true)
            $wiki->active = false;
        else
            $wiki->active = true;
            
        $wiki->save();
        
     return redirect('wiki/'.$id);
    }
    
    
     /**
     * Search documents by request parameters.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if(empty($request->all())) return redirect('/wiki');
        $searchInput = $request->get('search');
        $search = $this->search->searchWiki( $request->all() );  
        $topCategories = WikiCategory::where('top_category',1)->get();
        
        // $newestWikiEntries = WikiPage::orderBy('created_at','DESC')->paginate(10, ['*'], 'neueste-beitraege');
        $newestWikiEntriesPagination = WikiPage::orderBy('created_at','DESC')->paginate(10, ['*'], 'neueste-beitraege');
        $newestWikiEntries = $this->document->generateWikiTreeview($newestWikiEntriesPagination);
        
        $myWikiPagesPagination = WikiPage::where('user_id', Auth::user()->id)->orderBy('created_at','DESC')->paginate(10, ['*'], 'meine-beitraege');
        $myWikiPages = $this->document->generateWikiTreeview($myWikiPagesPagination);
        
        // dd($search);
        return view('wiki.index', compact('search','topCategories','newestWikiEntries','newestWikiEntriesPagination','myWikiPages','myWikiPagesPagination','searchInput')); 
    }
    
    /**
     * Wiki admin managment
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function managmentAdmin()
    {  
        if( ViewHelper::universalHasPermission( array(15,16) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
            
        $data = array();
        $wikies = WikiPage::orderBy('created_at','desc')->get();
        $statuses = WikiPageStatus::all();
        $categories = WikiCategory::all();
        $users = WikiPage::orderBy('id','asc')->pluck('user_id')->toArray();
        $wikiUsers = User::whereIn('id',$users)->get();
        $admin = true;
        
        return view('wiki.managment', compact('data','wikies', 'statuses','wikiUsers','categories','admin')); 
    }
    
    /**
     * Wiki admin managment
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function managmentUser()
    {  
        if( ViewHelper::universalHasPermission( array(15,16) ) == false  )
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
            
        $data = array();
        $wikies = WikiPage::orderBy('created_at','desc')->get();
        $statuses = WikiPageStatus::all();
        $categories = WikiCategory::all();
        $users = WikiPage::orderBy('id','asc')->pluck('user_id')->toArray();
        $wikiUsers = User::whereIn('id',$users)->get();
        $admin = false;
        
        return view('wiki.managment', compact('data','wikies', 'statuses','wikiUsers','categories','admin')); 
    }
    
     /**
     * Search documents by request parameters.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchManagment(Request $request)
    {
        
        $data = new \StdClass(); 
        foreach($request->all() as $k => $v){
            $data->$k = $v;
        }
        $wikies = $this->search->searchManagmentSearch($data);
        $statuses = WikiPageStatus::all();
        $categories = WikiCategory::all();
        $users = WikiPage::orderBy('id','asc')->pluck('user_id')->toArray();
        $wikiUsers = User::whereIn('id',$users)->get();
        if($data->admin == true)
            $admin = true;
        else 
            $admin = false;
        
       return view('wiki.managment', compact('data','wikies', 'statuses','wikiUsers','categories','admin')); 
    }
    
    /**
     * Duplicate existing wiki page
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function duplicate($id)
    {
        $originalWiki = WikiPage::find($id);
        
        $wiki = $originalWiki->replicate();
        $wiki->name .= ' (Kopie)';
        $wiki->save();
        //  dd($wiki);
        
        return redirect('/wiki/'.$wiki->id.'/edit');
    }
}
