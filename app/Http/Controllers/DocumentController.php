<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Request as RequestMerge;

use App\Http\Requests;
use App\Http\Requests\DocumentRequest;
use Auth;

use DB;
use App\Document;
use App\DocumentType;
use App\DocumentMandant;
use App\DocumentUpload;
use App\Role;
use App\IsoCategory;
use App\User;
use App\Mandant;
use App\MandantUser;
use App\MandantUserRole;
use App\Adressat;
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
     * Display a listing of the resource.
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editPdfUpload($id)
    {
        $adressats = Adressat::all();
        $data = Document::find($id);
        $backButton = '/dokumente/'.$data->id.'/edit';
        $setDocument = $this->document->setDocumentForm($data->document_type_id, $data->pdf_upload );
        $url = $setDocument->url;
        $form = $setDocument->form;
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
        if($model == null){
            
            return redirect('dokumente/create')->with( array('message'=>'No Document with that id') );
        }
            
        $filename = '';
        $path = $this->pdfPath;
        //dd($request->get('model_id') );
        if( $request->file() )
            $fileNames = $this->fileUpload($model,$path,$request->file() );
            
           //not summary, it is inhalt + attachment
         
        $data = Document::findOrNew($request->get('model_id'));
        $data->fill($request->all() );
        $data->save();
        $id = $data->id; 
        
        RequestMerge::merge(['document_id' => $id,'variant_number' => 1,'document_status_id'=>1/*maybe auto*/] );
        //$inputs = $request->except(array('_token', '_method','save','next') );
        $editorVariantId = EditorVariant::where('document_id',$id)->first();
        if( $editorVariantId == null)
            $editorVariantId = new EditorVariant();
        $editorVariantId->document_id = $id;
        $editorVariantId->variant_number = 1;
        $editorVariantId->document_status_id = 1;
        $editorVariantId->inhalt = $request->get('inhalt');
        $editorVariantId->save();
        $editorVariantId::where('document_id',$id)->first();
        
        if(count($fileNames) )
            foreach( $fileNames as $fileName ){
                $documentAttachment = new DocumentUpload();
                $documentAttachment->editor_variant_id = 1;
                $documentAttachment->file_path = $fileName;
                $documentAttachment->save();
            }
        //$editorVariant = EditorVariant::create($request->all() );
       
        session()->flash('message',trans('mandantForm.success'));
        if( $request->has('save') ){
            $adressats = Adressat::all();
            $setDocument = $this->document->setDocumentForm( $data->document_type_id, $data->pdf_upload  );
            $url = $setDocument->url;
            $form = $setDocument->form;
            $backButton = 'dokumente/'.$data->id.'/edit';
            return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats') );
        }
        $backButton = 'dokumente/pdf-upload/'.$data->id.'/edit';
       
         //return redirect()->action('DocumentController@anlegenRechteFreigabe',array($data->id, $data) );
        return redirect('dokumente/rechte-und-freigabe/'.$id );
    }
    
    
     /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anlegenRechteFreigabe($id,$backButton=null)
    {   
        $data = Document::find($id);
        $backButton = '/dokumente/editor/'.$data->id.'/edit';
        if($data->pdf_upload == true || $data->pdf_upload==1)
            $backButton = '/dokumente/pdf-upload/'.$data->id.'/edit'; 
        elseif( $data->pdf_upload == false &&  $data->document_type_id == 5 )  
            $backButton = '/dokumente/dokumente-upload/'.$data->id.'/edit'; 
        $collections = array();
        $roles = Role::all();
        //find variants
        $variants = EditorVariant::where('document_id',$data->id)->get();
        //
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
        $mandantUsersTable = MandantUser::whereIn('id',$mandantUserRoles)->pluck('user_id');
        $mandantUsers = User::whereIn('id',$mandantUsersTable)->get();
        $mandants = Mandant::whereNull('deleted_at')->get();
       
        $documentMandats = DocumentMandant::where('document_id',$data->id)->get();
        
        return view('dokumente.anlegenRechteFreigabe', compact('collections',
        'mandants','mandantUsers','variants','roles','data','backButton') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveRechteFreigabe(Request $request,$id)
    {
        dd($request->all() );
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
        if($data == null)
            return redirect('dokumente/create');
            
        if(isset($data->date_expired) && $data->date_expired !=null)
            $data->date_expired = Carbon::parse($data->date_expired)->format('d.m.Y.');
        $url = 'PATCH';
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
        $adressats = Adressat::all();
        RequestMerge::merge(['version' => 1] );
        $data = Document::find( $id )->fill( $request->all() )->save();
        $data = Document::find($id);
        $variant = $data->editorVariant();
        $setDocument = $this->document->setDocumentForm($request->get('document_type_id'), $request->get('pdf_upload')  );
        $url = $setDocument->url;
        $form = $setDocument->form;
        $backButton = 'dokumente/'.$data->id.'/edit';
        session()->flash('message',trans('mandantForm.success'));
        return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats') );
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
		        if( is_array($file)){
		            	   
		            foreach($file as $f){
		                if($f !== NULL)
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
		$newName = str_slug($model->name).'-'.date("d-m-Y-H:i:s").'-'.substr(time(),0,4).'.'.$file->getClientOriginalExtension();
		$path = "$folder/$newName";
        $filename = $file->getClientOriginalName();
        $uploadSuccess = $file->move($folder, $newName );
      	\File::delete($folder .'/'. $filename);
      	return $newName;
    }
    
    private function nextFormChecker($model,$linkMe=''){
        
    }
    
}
