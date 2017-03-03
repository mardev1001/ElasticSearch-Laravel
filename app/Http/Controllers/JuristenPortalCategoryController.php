<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ViewHelper;
use App\JuristCategory;

class JuristenPortalCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!ViewHelper::universalHasPermission(array(6, 35))) {
            return redirect('/')->with('message', trans('documentForm.noPermission'));
        }
        $juristenCategories = $juristCategoryOptions = JuristCategory::all();

        return view('juristenportal-kategorien.index', compact('juristenCategories', 'juristCategoryOptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->has('category_id')) {
            $request->merge(['jurist_category_parent_id' => $request->get('category_id')]);
        }
        if ($request->has('parent') && $request->get('parent') == 'on') {
            $request->merge(['parent' => 1]);
        }
        $request->merge(['slug' => str_slug($isoCategory->name), 'active' => true]);
        $juristenCategory = JuristCategory::create($request->all());

        return back()->with('message', 'Jurist Kategorie erfolgreich gespeichert.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $juristenCategory = JuristCategory::find($id);

        if ($request->has('activate')) {
            $status = !$request->input('activate');
            $juristenCategory->active = $status;
        }

        if ($request->has('category_id')) {
            if ($juristenCategory->parent) {
                return back()->with('error', 'Hauptkategorie kann nicht als Unterkategorie gespeichert werden.');
            }
            $juristenCategory->iso_category_parent_id = $request->input('category_id');
        }

        $juristenCategory->name = $request->input('name');
        $juristenCategory->slug = str_slug($juristenCategory->name);
        $juristenCategory->save();

        return back()->with('message', 'Jurist Kategorie erfolgreich aktualisiert.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $juristenCategory = JuristCategory::find($id);
        $juristenCategory->delete();

        return back()->with('message', 'Kategorie gelöscht.');
    }
}
