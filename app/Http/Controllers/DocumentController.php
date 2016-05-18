<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;

use Request as RequestMerge;

use App\Http\Requests;
use App\Http\Requests\DocumentRequest;
use Auth;

use DB;
use App\Document;
use App\Role;
use App\DocumentType;
use App\IsoCategory;
use App\User;
use App\MandantUser;
use App\MandantUserRole;
use App\Adressat;
use App\DocumentUpload;
use App\EditorVariant;
use App\EditoVariantDocument;//latest active document
use App\Http\Repositories\DocumentRepository;
class DocumentController extends Controller
{
    /**
     * Class constructor
     *
     */
    public function __construct(DocumentRepository $docRepo)
    {
        $this->document =  $docRepo;
        $this->pdfPath = public_path().'/files/documents/';
     }
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->first()->mandant_id;
        
        $url = '';
        $documentTypes = DocumentType::all();
        $isoDocuments = IsoCategory::all();
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
       
        $mandantUsers = User::leftJoin('mandant_users', 'users.id', '=', 'mandant_users.user_id')
        ->where('mandant_id', $mandantId)
        ->whereNotIn('users.id',array(Auth::user()->id) )->get();
         //dd($mandantUserRole2s);
         
        return view('formWrapper', compact('url','documentTypes','isoDocuments','mandantUsers') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $adressats = Adressat::all();
        //DB::enableQueryLog();
        RequestMerge::merge(['version' => 1] );
        $data = Document::create($request->all() );
        //dd(DB::getQueryLog());
        $setDocument = $this->document->setDocumentForm($request->get('document_type_id'), $request->get('pdf_upload')  );
        $url = $setDocument->url;
        $form = $setDocument->form;
        $backButton = 'dokumente/'.$data->id.'/edit';
        session()->flash('message',trans('mandantForm.success'));
        return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats') );
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pdfUpload(Request $request)
    {
     
        $model = Document::find($request->get('model_id'));
        $filename = '';
        $path = $this->pdfPath;
        if( $request->file() )
            $fileNames = $this->fileUpload($model,$path,$request->file() );
            
           //not summary, it is inhalt + attachment
        $data = Document::findOrNew($request->get('model_id'));
        $data->fill($request->all() );
        $data->save();
        $id = $data->id; 
       
        RequestMerge::merge(['document_id' => $id,'variant_number' => 1,'document_status_id'=>1/*maybe auto*/] );
        $editorVariantId = EditorVariant::create($request->all() )->id;
        foreach( $fileNames as $fileName ){
            $documentAttachment = new DocumentUpload();
            $documentAttachment->editor_variant_id = $editorVariantId;
            $documentAttachment->file_path = $fileName;
            $documentAttachment->save();
            
        }
        $editorVariant = EditorVariant::create($request->all() );
        //dd($editorVariant);
        //Merge additional variables to request and Create document upload entry
       
        
        //Merge additional variables to request and Create document active variant
        //  $editorVarinatDocument = EditoVariantDocument::create($request->all() );
        
       
        
        session()->flash('message',trans('mandantForm.success'));
        
        return redirect('dokumente/rechte-und-freigabe/'.$id );
    }
    
     /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anlegenRechteFreigabe($id)
    {
        $data = Document::find($id);
        $collections = array();
        $roles = Role::all();
        $variants = EditorVariant::where('document_id',$data->id)->get();
        //
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
        $mandantUsersTable = MandantUser::whereIn('id',$mandantUserRoles)->pluck('user_id');
        $mandantUsers = User::whereIn('id',$mandantUsersTable)->get();
        
        return view('dokumente.anlegenRechteFreigabe', compact('collections','mandantUsers','variants','roles','data') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveRechteFreigabe(Request $request,$id)
    {
        //dd($request->all() );
        $data = Document::find($id);
        $collections = array();
        $approvalMandants = Mandant::all();
        $roles = Role::all();
        $variants = EditorVariant::where('document_id',$data->id)->get();
        
        return view('dokumente.anlegenRechteFreigabe', compact('collections','variants','roles','approvalMandants',
        'data') );
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
        $data = Document::find($id);
        $data->date_expired = Carbon::parse($data->date_expired)->format('d.m.Y.');
        $method = 'PATCH';
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->first()->mandant_id;
        $url = '';
        $documentTypes = DocumentType::all();
        $isoDocuments = IsoCategory::all();
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
        
        $mandantUsers = User::leftJoin('mandant_users', 'users.id', '=', 'mandant_users.user_id')
        ->where('mandant_id', $mandantId)
        ->whereNotIn('users.id',array(Auth::user()->id) )->get();
         //dd($mandantUserRole2s);
         
        return view('formWrapper', compact('data','method','url','documentTypes','isoDocuments','mandantUsers') );
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
        // merge user_id, manual AI version, version_parent 
        /*
            For repository
            - if owner ID not set, set current user
        */
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
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function isoDocument()
    {
        $counter = 0;
       
        $users = $this->document->generateDummyData('users', array(),false );
        $data = json_encode( $this->document->generateDummyData('document 5.4.2016', $users, false ) );
          
        $files = $this->document->generateDummyData('anhang', array(), false );
        $data2 = json_encode( $this->document->generateDummyData('document 06.5.2016', $files, false ) );
        
        $files2 = $this->document->generateDummyData('anhang', array(), false );
        $data3 = json_encode( $this->document->generateDummyData('document 11.5.2016', $files2, false ) );
        
        return view('dokumente.isoDocument', compact('data','data2','data3','counter') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rundschreiben()
    {
        $counter = 0;
       
        $data = json_encode(  $this->document->generateDummyData('Anhag dokumente', array(),false ) );
          
        $comment = $this->document->generateDummyData('Lorem ipsum comment', array(), false );
        $data2 = json_encode( $this->document->generateDummyData('Herr Engel - Betreff', $comment ) );
        
        return view('dokumente.rundschreiben', compact('data','data2','counter') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rundschreibenPdf()
    {
        $counter = 0;
       
        $data = json_encode(  $this->document->generateDummyData('Anhag dokumente', array(),false ) );
          
        $comment = $this->document->generateDummyData('Lorem ipsum comment', array(), false );
        $data2 = json_encode( $this->document->generateDummyData('Herr Engel - Betreff', $comment ) );
        
        return view('dokumente.rundschreibenPdf', compact('data','data2','counter') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentUpload()
    {
        $counter = 0;
       
        $data = json_encode(  $this->document->generateDummyData('Anhag dokumente', array(),false ) );
          
        $comment = $this->document->generateDummyData('Lorem ipsum comment', array(), false );
        $data2 = json_encode( $this->document->generateDummyData('Herr Engel - Betreff', $comment ) );
        
        return view('dokumente.documentUpload', compact('data','data2','counter') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rundschreibenQmr()
    {
        $counter = 0;
        $document = $this->document->generateDummyData('document single',array(), false);
        $users = $this->document->generateDummyData('users', $document );
        $data = json_encode( $this->document->generateDummyData('dokumente', $users ) );
        
        $document2 = $this->document->generateDummyData('document single',array(), false);
        $users2 = $this->document->generateDummyData('users', $document2 );
        $data2 = json_encode( $this->document->generateDummyData('dokumente', $users2 ) );
        
        return view('dokumente.circularQMR', compact('data','data2','counter') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rundschreibenNews()
    {
        $counter = 0;
        $document = $this->document->generateDummyData('document single');
        $users = $this->document->generateDummyData('users', $document );
        $data = $this->document->generateDummyData('dokumente', $users );
        
        $document2 = $this->document->generateDummyData('document single',array(), false);
        $users2 = $this->document->generateDummyData('users', $document2 );
        $data2 = json_encode( $this->document->generateDummyData('dokumente', $users2 ) );
        
        return view('dokumente.rundschreibenNews', compact('data','data2','counter') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentTemplates()
    {
        $counter = 0;
        $document = $this->document->generateDummyData('document single',array(), false);
        $users = $this->document->generateDummyData('users', $document );
        $data = json_encode( $this->document->generateDummyData('dokumente', $users ) );
        
        $document2 = $this->document->generateDummyData('document single',array(), false);
        $users2 = $this->document->generateDummyData('users', $document2 );
        $data2 = json_encode( $this->document->generateDummyData('dokumente', $users2 ) );
        
        return view('dokumente.documentTemplates', compact('data','data2','counter') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentType()
    {
        $counter = 0;
        $document = $this->document->generateDummyData('document single');
        $users = $this->document->generateDummyData('users', $document );
        $data = $this->document->generateDummyData('dokumente', $users );
        
        return view('dokumente.documentType', compact('data','counter') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function isoCategories()
    {
        $counter = 0;
        $document = $this->document->generateDummyData('document single');
        $users = $this->document->generateDummyData('users', $document );
        $data =  json_encode( $this->document->generateDummyData('dokumente', $users) );
        
        return view('dokumente.isoCategories', compact('data','counter') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anlegen()
    {
        $collections = array();
        $counter = 0;
        $document = $this->document->generateDummyData('document single');
        $users = $this->document->generateDummyData('users', $document );
        $data =  json_encode( $this->document->generateDummyData('dokumente', $users) );
        
        return view('dokumente.anlegen', compact('collections','data','counter') );
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function anlegenStore(Request $request)
    {
        
    }
    
    /**
     * Display the statistics for the document with the passed ID parameter.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentStats($id)
    {
        $data = '';
        return view('dokumente.statistik', compact('data') );
    }
    
    /**
     * Display the history for the document with the passed ID parameter.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentHistory($id)
    {
        $variants = $this->document->generateDummyData('Anhang');
        $documents = $this->document->generateDummyData('Variante', $variants);
        $history = $this->document->generateDummyData('Dokument', $documents);
        $data = json_encode($history);
        return view('dokumente.historie', compact('data') );
    }
    
    private function fileUpload($model,$path,$files){
      
        $folder = $this->pdfPath.'/'.str_slug($model->name);
        //$fullFolderPath = public_path().'/'.$folder;
        $uploadedNames = array();
        if ( ! \File::exists( $folder ) ) {
			\File::makeDirectory( $folder, $mod=0777,true,true); 
		}
		if( is_array($files) ){
		    $uploadedNames = array();
		   
		    foreach($files as $file){
		        if( is_array($file) ){
		            foreach($file as $f){
		                 $uploadedNames[] =  $this->moveUploaded($f,$folder,$model);
		            }
		        }
		        else
		      $uploadedNames[] =  $this->moveUploaded($file,$folder,$model);
		    }
		}
		else
            $uploadedNames[] = $this->moveUploaded($files,$folder,$model);
        return $uploadedNames;
	
    }
    
    private function moveUploaded($file,$folder,$model){
        //$filename = $image->getClientOriginalName();
		$newName = str_slug($model->name).'-'.date("d-m-Y-H_i_s").'.'.$file->getClientOriginalExtension();
		$path = "$folder/$newName";
        $filename = $file->getClientOriginalName();
        $uploadSuccess = $file->move($folder, $newName );
      	\File::delete($folder .'/'. $filename);
      	return $newName;
    }
    
}
