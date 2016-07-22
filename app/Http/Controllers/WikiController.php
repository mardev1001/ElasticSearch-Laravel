<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\DocumentRequest;

use App\WikiPage;
use App\WikiPageStatus;
use App\WikiRole;
use App\WikiCategory;
use App\WikiCategoryUser;
use App\WikiPageHistory;

class WikiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('wiki.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
        return view('formWrapper', compact('data','wikiCategories','wikiStatuses','wikiRoles') );
        
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
}
