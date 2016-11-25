<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Request as RequestMerge;
use App\Http\Repositories\SearchRepository;

use App\WikiRole;
use App\WikiCategory;
use App\WikiCategoryUser;
use App\User;
use App\Role;
use App\MandantRole;
use App\MandantUser;
use App\WikiPage;
use App\Http\Repositories\DocumentRepository;
use App\Helpers\ViewHelper;

class WikiCategoryController extends Controller
{
    
    public function __construct(SearchRepository $searchRepo, DocumentRepository $docRepo)
    {
      $this->middleware('wiki')->only('show');
      $this->middleware('wiki.editor')->except('show');
      $this->document = $docRepo;
      $this->search = $searchRepo;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usersWithWikiRoles = ViewHelper::getUsersByRole( array(15) ); //Wiki Redakteur == 15
        // dd($usersWithWikiRoles);
        $wikiCategories = WikiCategory::all();
        $wikiRoles = WikiRole::all();
        $users = User::whereIn('id',$usersWithWikiRoles)->get();
        $roles = Role::where('wiki_role',1)->get();
        $roleSelect = '';
        $userSelect = '';
        
        return view('wiki.categories', compact('wikiCategories', 'wikiRoles','users','roles') );
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
        $request->merge( array('all_roles' => 1) );
        $wikiCat = WikiCategory::create($request->all());
        session()->flash('message',trans('wiki.wikiCategoryCreateSuccess'));
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = WikiCategory::find($id);
        
     
        $query = WikiPage::where('category_id',$id);
        if( ViewHelper::universalHasPermission(array(15)) == false ){
            $query->whereNotIn('status_id',array(1,3) );
        }
           
        $categoryEntries = $query->paginate(12);   
        $categoryEntriesTree = $this->document->generateWikiTreeview( $categoryEntries );
        // $categoryEntries = WikiPage::where('category_id',$id)->paginate(12);
        
        return view('wiki.category', compact('category','categoryEntries','categoryEntriesTree') ); 
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
        $allRoles = false;
        foreach($request->all() as $k => $r){
             if (strpos($k, 'role_id') !== false ){
                 if($r == 'Alle')
                    $allRoles = true;
             }
        }
        
       $wikiCat = WikiCategory::find($id);
        if( !$request->has('top_category') )
            RequestMerge::merge(['top_category' => 0] );
        
        $wikiCat->fill($request->all());
        $wikiCat->save();
        $wkuArray = array();
        $rolesArray = array();
        if( $request->has('user_id') ){
            WikiCategoryUser::where('wiki_category_id',$id)->delete();
            foreach($request->get('user_id') as $uid){
                $wku= WikiCategoryUser::where('user_id',$uid)->where('wiki_category_id',$id)->first();
                if( $wku == null ){
                    $nWku = new WikiCategoryUser();
                    $nWku->user_id = $uid;
                    $nWku->wiki_category_id = $id;
                    $nWku->save();
                }
                    $wkuArray[] = $uid;
            }
        }
        else
            $allWikiUsers = WikiCategoryUser::where('wiki_category_id',$id)->delete(); 
            //finish tihs part
            
        if( $request->has('role_id') ){
            
            if( in_array('Alle',$request->get('role_id') )  ){
                $wikiCat->all_roles = 1;
                $wikiCat->save();
                WikiRole::where('wiki_category_id',$id)->delete(); 
               //$wikiRoles =WikiRole::where('wiki_category_id',$id)->pluck('role_id')->toArray();
               /*$roles= Role::where('wiki_role',1)->get();
               foreach($roles as $r){
                   if( !in_array($r->id,$wikiRoles) ){
                        $wr = new WikiRole();
                        $wr->role_id = $r->id;
                        $wr->wiki_category_id = $id;
                        $wr->save();
                   }
                   
               }*/
            }
            else{
                $wikiCat->all_roles = 0;
                $wikiCat->save();
                
                WikiRole::where('wiki_category_id',$id)->delete(); 
                foreach($request->get('role_id') as $gid){
                    $wikiRole= WikiRole::where('role_id',$gid)->where('wiki_category_id',$id)->first();
                    if( $wikiRole == null ){
                        $wikiRole = new WikiRole();
                        $wikiRole->role_id = $gid;
                        $wikiRole->wiki_category_id = $id;
                        $wikiRole->save();
                    }
                        $wkuArray[] = $gid;
                }
            }
        }
        else
             WikiRole::where('wiki_category_id',$id)->delete(); 
            
        session()->flash('message',trans('wiki.wikiCategoryCreateSuccess'));
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
        // dd($id);
        $destory = WikiCategory::destroy($id);
        return redirect()->back();
    }
    
     /**
     * Search documents by request parameters.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $id = $request->get('category');
        $category = WikiCategory::find($id);
        
     
        $query = WikiPage::where('category_id',$id);
        if( ViewHelper::universalHasPermission(array(15)) == false ){
            $query->whereNotIn('status_id',array(1,3) );
        }
        $querySearch = $this->search->searchWikiCategories( $request->all() );  
        $categoryEntries = $querySearch->paginate(12);   
        $categoryEntriesTree = $this->document->generateWikiTreeview( $categoryEntries );
        // $categoryEntries = WikiPage::where('category_id',$id)->paginate(12);
        
        return view('wiki.category', compact('category','categoryEntries','categoryEntriesTree') ); 
    }
}
