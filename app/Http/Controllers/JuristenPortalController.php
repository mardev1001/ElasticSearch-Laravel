<?php

namespace App\Http\Controllers;

use Artisan;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Helpers\ViewHelper;

use App\JuristCategory;
use App\JuristCategoryMeta;
use App\JuristCategoryMetaField;
use App\JuristCategoryMetaFieldValues;
use App\JuristFile;
use App\JuristFileAttachment;
use App\JuristFileComment;
use App\JuristFileUpload;
use App\JuristDocumentsMandant;
use App\Document;
use App\EditorVariant;
use App\DocumentUpload;

use App\Http\Repositories\DocumentRepository;


class JuristenPortalController extends Controller
{
    /**
     * Class constructor.
     */
    public function __construct(DocumentRepository $docRepo)
    {
        $this->document = $docRepo;
        $this->uploadPath = public_path().'/files/contacts/';
    
        $this->portalOcrUploads = public_path().'/files/juristenportal/ocr-uploads/';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (ViewHelper::universalHasPermission(array(35, 36), false) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $juristenCategories = JuristCategory::where('parent',1)->where('active',1)->get();
        
        return view('juristenportal.index', compact('juristenCategories') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (ViewHelper::universalHasPermission(array(35, 36), false) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
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
        if (ViewHelper::universalHasPermission(array(35, 36), false) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
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
        if (ViewHelper::universalHasPermission(array(35, 36), false) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
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
        if (ViewHelper::universalHasPermission(array(35, 36), false) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
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
        if (ViewHelper::universalHasPermission(array(35, 36), false) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function uploadView()
    {
        if (ViewHelper::universalHasPermission(array(35, 36), false) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $query = Document::whereNull('document_type_id')
        ->orderBy('documents.created_at', 'desc')->limit(50);
        $documentsMy = $query->where(function ($query) {
                $query->where('owner_user_id', Auth::user()->id)
                ->orWhere('user_id', Auth::user()->id);
                $query->orWhereIn('documents.id', [Auth::user()->id]);
         })->paginate(10, ['*'], 'my-dokumente');
        $documentsMyTree = $this->document->generateTreeview($documentsMy, array('pageHome' => true, 'myDocuments' => true, 'noCategoryDocuments' => true,
        'showAttachments' => true, 'showHistory' => true));
       
        $documentsAll = $query->paginate(10, ['*'], 'alle-dokumente');
        $documentsAllTree = $this->document->generateTreeview($documentsAll, array('pageHome' => true, 'myDocuments' => true, 'noCategoryDocuments' => true,
        'showAttachments' => true, 'showHistory' => true));
        // $documentsNew = $this->document->getUserPermissionedDocuments($documentsNew, 'neue-dokumente', array('field' => 'documents.date_published', 'sort' => 'desc'), $perPage = 10);
        // $documentsNewTree = $this->document->generateTreeview($documentsNew, 
        // array('pageHome' => true, 'showAttachments' => true, 'showHistory' => true));

        return view('juristenportal.upload', compact('documentsMy','documentsMyTree','documentsAll','documentsAllTree'));
    }
    
    
       
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function akten()
    {
        if (ViewHelper::universalHasPermission(array(7, 34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }

        return view('juristenportal.akten');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeAkten(Request $request)
    {
        
        
        return redirect()->back()->with('messageSecondary', trans('juristenPortal.addedSomething'));
    }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function metaInfo()
    {
        if (ViewHelper::universalHasPermission(array(7, 34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $categories = JuristCategoryMeta::all();

        return view('juristenportal.metaInfo', compact('categories'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeMetaInfo(Request $request)
    {
        // dd( $request->all() );
        if(!$request->has('name')){
            redirect()->back()->with('messageSecondary', trans('juristenportal.noCategoryName'));
        }
        $category = JuristCategoryMeta::create($request->all());
        // dd($category);
        if($request->has('meta-names') && count($request->get('meta-names'))){
            $request->merge(['jurist_category_meta_id'=> $category->id]);
            foreach($request->get('meta-names') as $meta){
                 $request->merge(['name'=> $meta]);
                 JuristCategoryMetaField::create($request->all());
            }
        }
        
        return redirect()->back()->with('messageSecondary', trans('inventoryList.inventoryAdded'));
    }
    
     /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateMetaInfo(Request $request, JuristCategoryMeta $juristenCategoryMeta)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
       
        $juristenCategoryMeta->fill($request->all())->save();

        return redirect()->back()->with('messageSecondary', trans('inventoryList.categoryUpdated'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function addJuristenCategoryMetaFields(Request $request, JuristCategoryMeta $juristenCategoryMeta)
    {
        $request->merge(['jurist_category_meta_id'=> $juristenCategoryMeta->id]);
        if($request->has('meta-names') && count($request->get('meta-names'))){
            foreach($request->get('meta-names') as $meta){
                 $request->merge(['name'=> $meta]);
                 JuristCategoryMetaField::create($request->all());
            }
        }
        
        return redirect()->back()->with('messageSecondary', trans('inventoryList.inventoryAdded'));
    }
    
     /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function updateMetaField(Request $request, $id)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $metaField = JuristCategoryMetaField::find($id);
        $metaField->fill($request->all())->save();
        
        return redirect()->back()->with('messageSecondary', trans('inventoryList.inventoryAdded'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateJuristenCategoryMetaFields(Request $request, JuristCategoryMeta $juristenCategoryMeta)
    {
        $request->merge(['jurist_category_meta_id'=> $juristenCategoryMeta->id]);
        if($request->has('meta-names') && count($request->get('meta-names'))){
            foreach($request->get('meta-names') as $meta){
                 $request->merge(['name'=> $meta]);
                 JuristCategoryMetaField::create($request->all());
            }
        }
        
        return redirect()->back()->with('messageSecondary', trans('inventoryList.inventoryAdded'));
    }
    
     /**
     * Delete the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteJuristenCategoryMeta(JuristCategoryMeta $juristenCategoryMeta)
    {
        if (ViewHelper::universalHasPermission(array(34)) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $juristenCategoryMeta->delete();

        return redirect()->back()->with('messageSecondary', trans('inventoryList.deletedJuristenCategoryMeta'));
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        if (ViewHelper::universalHasPermission(array(35, 36), false) == false) {
            return redirect('/')->with('messageSecondary', trans('documentForm.noPermission'));
        }
        $uploaded = $this->fileUpload($request->file);
        
        if ($uploaded == false) {
            return redirect()->back()->with('messageSecondary', 'Upload fehlgeschlagen');
        }
        else {
            Artisan::call('juristenportal:import');
            return redirect()->back()->with('messageSecondary', 'Dateien hochgeladen');
        }
    }

    /**
     * Process files for upload.
     *
     * @param array $files
     *
     * @return \Illuminate\Http\Response
     */
    private function fileUpload($files)
    {
        $folder = $this->portalOcrUploads;
        $uploadedNames = array();
        if (!\File::exists($folder)) {
            \File::makeDirectory($folder, $mod = 0777, true, true);
        }
        if (is_array($files)) {
            $uploadedNames = array();
            $counter = 0;
           //  dd($files);
            foreach ($files as $k => $file) {
                ++$counter;
                if (is_array($file)) {
                    foreach ($file as $f) {
                        if ($f !== null) {
                            if (ViewHelper::fileTypeAllowed($file)) {
                                $uploadedNames[] = $this->moveUploaded($f, $folder, $k);
                            }
                        }
                    }
                } else {
                    if (ViewHelper::fileTypeAllowed($file)) {
                        $uploadedNames[] = $this->moveUploaded($file, $folder, $k);
                    }
                }
            }
        } else {
            if (ViewHelper::fileTypeAllowed($files)) {
                $uploadedNames[] = $this->moveUploaded($files, $folder);
            }
        }

        if (count($uploadedNames)) {
            return $uploadedNames;
        } else {
            return false;
        }
    }

    /**
     * Move files from temp folder and rename them.
     *
     * @param file object $file
     * @param string      $folder
     *
     * @return string $newName
     */
    private function moveUploaded($file, $folder, $counter = 0)
    {
        //$filename = $image->getClientOriginalName();
        $diffMarker = time() + $counter;
        $newName = date('d-m-Y-H:i:s').'-'.$diffMarker.'.'.$file->getClientOriginalExtension();
        $path = "$folder/$newName";
        +$filename = $file->getClientOriginalName();
        $uploadSuccess = $file->move($folder, $newName);
        \File::delete($folder.'/'.$filename);

        return $newName;
    }
}
