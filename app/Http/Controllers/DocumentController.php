<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Request as MergeRequest;

use App\Http\Requests;
use App\Http\Requests\DocumentRequest;

use DB;
use App\Document;
use App\Role;
use App\DocumentType;
use App\IsoCategory;
use App\User;
use App\Adressat;
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
        $this->pdfPath = public_path().'/document/pdf/';
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
        $url = '';
        $documentTypes = DocumentType::all();
        $isoDocuments = IsoCategory::all();
        $mandantUsers = User::all();
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
        $data = Document::create($request->all() );
        //dd(DB::getQueryLog());
        $setDocument = $this->document->setDocumentForm($request->get('document_type_id'), $request->get('pdf_upload')  );
        $url = $setDocument->url;
        $form = $setDocument->form;
        session()->flash('message',trans('mandantForm.success'));
        return view('dokumente.formWrapper', compact('data','form','url','adressats') );
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function pdfUpload(Request $request)
    {
       //dd( $request->all() );
      //function or inFunction
        $model = Document::find($request->get('model_id'));
        $filename = '';
        $path = $this->pdfPath;
        if( $request->file() ){
            $filename = $this->fileUpload($model,$path,$request->file() );
            
        }
        $data = Document::findOrNew($request->get('model_id'));
        //dd($data);
        //dd(DB::getQueryLog());
        session()->flash('message',trans('mandantForm.success'));
        
        return redirect()->action('DocumentController@anlegenRechteFreigabe',array('data'=>$data) );
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
        return view('formWrapper', compact('data'));
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
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anlegenRechteFreigabe($data)
    {
        dd($data);
        $collections = array();
        $data = '';
        return view('dokumente.anlegenRechteFreigabe', compact('collections','data') );
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
      
        $folder = str_slug($model->name);
        $fullFolderPath = public_path().'/'.$folder;
        if ( ! \File::exists( $fullFolderPath ) ) {
			\File::makeDirectory( $fullFolderPath, $mod=0777,true,true); 
		}
		if( is_array($files) ){
		    $uploadedNames = array();
		    foreach($files as $file){
		      $uploadedNames =  $this->moveUploaded($file,$folder,$model);
		    }
		}
		else
            $uploadedNames = $this->moveUploaded($files,$folder,$model);
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
