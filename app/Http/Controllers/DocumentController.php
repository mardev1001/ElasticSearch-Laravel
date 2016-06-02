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
use App\DocumentCoauthor;
use App\DocumentType;
use App\DocumentMandant;
use App\DocumentMandantMandant;
use App\DocumentMandantRole;
use App\DocumentUpload;
use App\DocumentApproval;
use App\Role;
use App\IsoCategory;
use App\User;
use App\Mandant;
use App\MandantUser;
use App\MandantUserRole;
use App\Adressat;
use App\EditorVariant;
use App\EditorVariantDocument;//latest active document
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
        $documentCoauthors = DocumentCoauthor::all();
        $documentTypes = DocumentType::all();
        $isoDocuments = IsoCategory::all();
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
       
        $mandantUsers = User::leftJoin('mandant_users', 'users.id', '=', 'mandant_users.user_id')
        ->where('mandant_id', $mandantId)
        ->whereNotIn('users.id',array(Auth::user()->id) )->get();
        // dd($mandantUsers);
         
        return view('formWrapper', compact('url', 'documentTypes', 'isoDocuments', 'mandantUsers', 'documentCoauthors') );
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
        $docType = DocumentType::find( $request->get('document_type_id') );
        
        RequestMerge::merge(['version' => 1] );
        $setDocument = $this->document->setDocumentForm($request->get('document_type_id'), $request->get('pdf_upload')  );
        
        // if($request->get('pdf_upload') &&  (strpos( strtolower($docType->name ) , 'rundschreiben') !== true) )
        //       RequestMerge::merge(['pdf_upload' => 0] );
            

        $data = Document::create($request->all() );
        $lastId = Document::orderBy('id','DESC')->first();
        $lastId->document_group_id = $lastId->id;
        $lastId->save();
        
        if($request->has('document_coauthor')){
            $coauthors = $request->input('document_coauthor');
            foreach($coauthors as $coauthor)
                DocumentCoauthor::create(['document_id'=> $lastId->id, 'user_id'=> $coauthor]);
        }

        $url = $setDocument->url;
        $form = $setDocument->form;
        $backButton = 'dokumente/'.$data->id.'/edit';
        session()->flash('message',trans('documentForm.documentCreateSuccess'));
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
        $dirty = false;
        $model = Document::find($request->get('model_id'));
        if($model == null){
            return redirect('dokumente/create')->with( array('message'=>'No Document with that id') );
        }
            
            // dd($request->get('adressat_id') );
        $filename = '';
        $path = $this->pdfPath;
        //   dd($path);
        //dd($request->get('model_id') );
        if( $request->file() )
            $fileNames = $this->fileUpload($model,$path,$request->file() );
            
           //not summary, it is inhalt + attachment
        //  dd($fileNames);
        $data = Document::findOrNew($request->get('model_id'));
        $data->fill($request->all() );
        $data->save();
        $dirty=$this->dirty($dirty,$data);
       
        $id = $data->id; 
      
        RequestMerge::merge(['document_id' => $id,'variant_number' => 1/*maybe auto*/] );
        //$inputs = $request->except(array('_token', '_method','save','next') );
        $editorVariantId = EditorVariant::where('document_id',$id)->first();
        if( $editorVariantId == null)
            $editorVariantId = new EditorVariant();
        $editorVariantId->document_id = $id;
        $editorVariantId->variant_number = 1;
        $editorVariantId->inhalt = $request->get('inhalt');
        $editorVariantId->save();
        $dirty=$this->dirty($dirty,$editorVariantId);
        $editorVariantId::where('document_id',$id)->first();
        
        if(count($fileNames) > 0 ){
            foreach( $fileNames as $fileName ){
                $documentAttachment = new DocumentUpload();
                $documentAttachment->editor_variant_id = $editorVariantId->id;
                $documentAttachment->file_path = $fileName;
                $documentAttachment->save();
                $dirty=$this->dirty($dirty,$documentAttachment);
            }
        }
        if($dirty == true)
         session()->flash('message',trans('documentForm.documentPdfCreateSuccess'));
        if( $request->has('save') ){
            $adressats = Adressat::all();
            $setDocument = $this->document->setDocumentForm( $data->document_type_id, $data->pdf_upload  );
            $url = $setDocument->url;
            $form = $setDocument->form;
            $backButton = 'dokumente/'.$data->id.'/edit';
            return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats') );
        }
        $backButton = 'dokumente/pdf-upload/'.$data->id.'/edit';
       
        if($request->has('attachment'))
            return redirect('dokumente/anhange/'.$id );
            
        return redirect('dokumente/rechte-und-freigabe/'.$id );
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
        
        return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentUpload(Request $request)
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
        $dirty=$this->dirty(false,$data);
        $id = $data->id; 
       
        $counter = 0;
        // var_dump('count number of files:'.count($fileNames));
        // echo '<br/>';
        if( isset($fileNames) && count($fileNames) > 0)
            foreach( $fileNames as $fileName ){
                $counter++;
                //Editor variant  upload
                $editorVariantId = EditorVariant::where('document_id',$id)->where('variant_number',$counter)->first();
                if( $editorVariantId == null)
                    $editorVariantId = new EditorVariant();
                $editorVariantId->document_id = $id;
                $editorVariantId->variant_number = $counter;
                // $editorVariantId->document_status_id = 1;
                $editorVariantId->inhalt = $request->get('inhalt');
                $editorVariantId->save();
                $dirty=$this->dirty($dirty,$editorVariantId);
                //  var_dump($editorVariantId);
                //  echo '<br/>';
                //Upload documents
                
                
                $documentAttachment = new DocumentUpload();
                $documentAttachment->editor_variant_id = $editorVariantId->id;
                $documentAttachment->file_path = $fileName;
                $documentAttachment->save();
                //  var_dump($documentAttachment);
                //  echo '<hr/>';
                $dirty=$this->dirty($dirty,$documentAttachment);
            }
        // dd($counter);
        if($dirty == true)
            session()->flash('message',trans('documentForm.documentUploadedCreateSuccess'));
        if( $request->has('save') ){
            $adressats = Adressat::all();
            $setDocument = $this->document->setDocumentForm( $data->document_type_id, $data->pdf_upload  );
            $url = $setDocument->url;
            $form = $setDocument->form;
            $backButton = 'dokumente/'.$data->id.'/edit';
            return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats') );
        }
        if($request->has('attachment'))
            return redirect('dokumente/anhange/'.$id );
            
        return redirect('dokumente/rechte-und-freigabe/'.$id );
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function documentEditor(Request $request)
    {
        //dd($request->all() );
        $model = Document::find($request->get('model_id'));
        if($model == null){
            
            return redirect('dokumente/create')->with( array('message'=>'No Document with that id') );
        }
        
         
        $data = Document::findOrNew($request->get('model_id'));
        $data->fill($request->all() );
        $data->save();
        $dirty=$this->dirty(false,$data);
        $id = $data->id; 
        
        //check if has varianttfirstcount
        foreach($request->all() as $k => $v)
            if (strpos($k, 'variant-') !== false){
              
                $variantNumber = $this->document->variantNumber($k);
                $editorVariant = EditorVariant::where('document_id',$id)->where('variant_number',$variantNumber)->first();
                 if( $editorVariant == null)
                    $editorVariant = new EditorVariant();
                $editorVariant->document_id = $id;
                $editorVariant->variant_number = $variantNumber;
                $editorVariant->inhalt = $v;
                $editorVariant->save();
                $dirty=$this->dirty($dirty,$editorVariant);
            }
        
        
         if($dirty == true)
            session()->flash('message',trans('documentForm.documentEditorCreateSuccess'));
        if( $request->has('save') ){
            $adressats = Adressat::all();
            $setDocument = $this->document->setDocumentForm( $data->document_type_id, $data->pdf_upload  );
            $url = $setDocument->url;
            $form = $setDocument->form;
            $backButton = 'dokumente/'.$data->id.'/edit';
            return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats') );
        }
         if($request->has('attachment'))
            return redirect('dokumente/anhange/'.$id );
        return redirect('dokumente/rechte-und-freigabe/'.$id );
    }
    
      
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function editDocumentEditor($id)
    {
        $adressats = Adressat::all();
        $data = Document::find($id);
        $backButton = 'dokumente/'.$data->id.'/edit';
        $setDocument = $this->document->setDocumentForm($data->document_type_id, $data->pdf_upload );
        $url = $setDocument->url;
        $form = $setDocument->form;
        // session()->flash('message',trans('documentForm.documentEditorEditSuccess'));
        return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats') );
    }
    
     /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function attachments($id,$backButton=null)
    {   
        $data = Document::find($id);
        $backButton = '/dokumente/editor/'.$data->id.'/edit';
        $url = '';
        //$variants, $documentAttachments, $existingDocuments
        $documents = Document::where('document_type_id',5)->get();// documentTypeId 5 = Vorlagedokument
        
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->first()->mandant_id;
        $attachmentArray = array();
        /*Check if document has editorVariant*/
        if( count( $data->editorVariant) > 0 )
            foreach( $data->editorVariant as $variant){
                // var_dump('Varinat Id:'.$variant->id);
                // echo '<br/>';
                
                        // dd($variant->editorVariantDocument);
                foreach($variant->editorVariantDocument as $evd){
                    if( $evd->document_id != null ){
                        $secondDocumentVariants = EditorVariant::where('document_id',$evd->document_id)->get();
                        // $additionalArray[] = $this->document->generateTreeview($secondDocumentVariants );
                        
                         
                        // echo '<br/>';
                       /* if( count( $secondDocumentVariants ) > 0 ){
                            foreach($secondDocumentVariants as $secDocVariant){
                                //  var_dump('$secDocVariant cnt:'.count( $secDocVariant ) );
                                //  echo '<br/>';
                                $additionalArray= array();
                                if( count($secDocVariant->documentUpload) > 0){
                                   $additionalArray[] = $this->document->generateTreeview($secDocVariant->documentUpload, false, false );
                                $attachmentArray[$variant->id] = $additionalArray;  
                                }
                            }
                        }*/
                    }
                  
                }
                // echo '<hr/>';
            }
            // dd($attachmentArray);
        /*End Check if document has attachments*/
        
        $url = '';
        $documentTypes = DocumentType::all();
        $isoDocuments = IsoCategory::all();
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
       
        $mandantUsers = User::leftJoin('mandant_users', 'users.id', '=', 'mandant_users.user_id')
        ->where('mandant_id', $mandantId)
        ->whereNotIn('users.id',array(Auth::user()->id) )->get();
         
        
        //if($data->pdf_upload == true || $data->pdf_upload==1)
        //     $backButton = '/dokumente/pdf-upload/'.$data->id.'/edit'; 
        // elseif( $data->pdf_upload == false &&  $data->document_type_id == 5 )  
        //     $backButton = '/dokumente/dokumente-upload/'.$data->id.'/edit'; 
        $collections = array();
        $roles = Role::all();
        //find variants
        $variants = EditorVariant::where('document_id',$data->id)->get();

        
        return view('dokumente.attachments', compact('collections','data','attachmentArray','documents','url','documentTypes',
        'isoDocuments','mandantUsers','backButton') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveAttachments(Request $request,$id)
    {
        // option 1-> dodat dokument kao attachmet za trenutnu variantu i vorlage
        // option 2-> kreira se skroz novi dokument, ali se dodaje kao attchment postojećem
        //document attachment is a record in editor_variants && editor_variant_document
        //  dd($request->all());
        $data = Document::find($id);
        
        $currentEditorVariant = $request->get('variant_id');
        $document = Document::find($request->get('document_id'));
        /*If option 1*/
            if($request->has('attach')){
                $currentDocumentLast =$data->lastEditorVariant[0];
                
                $foreignDocumentUploads = $document->documentUploads;
                 
                if( !$foreignDocumentUploads->isEmpty() ){
                    /*
                        Opcija 1 razložena
                        za svaki document upload od drugog documenta
                        dodaj i editor_variant_documents --->Kako razlikovat attachmente od dokumenata ako je ista varijanta?
                        document_id u editor_variant_documents je foreign key!
                        i documentUploads uzmi path od drugog dokumenta i stavi editor_variant_id od trenutnog
                    */
                    $currDocEv = EditorVariant::find($currentEditorVariant);
                    
                    $newAttachment = new EditorVariantDocument();
                    $newAttachment->editor_variant_id = $currentEditorVariant;
                    $newAttachment->document_id = $document->id;
                    $newAttachment->document_status_id = 1;
                    $newAttachment->save();
            }
        }
        
        /*If option 2*/
        else{
            /*
                Opcija 2 razložena
                kreira se skroz novi dokument i ponovni radnju iz prvog
            */
            RequestMerge::merge(['version' => 1,'document_type_id'=>1] );
            
            /*Create a new document*/
            $data = Document::create($request->all() );
            $lastId = Document::orderBy('id','DESC')->first();
            $lastId->document_group_id = $lastId->id;
            $lastId->save();
            
            /*Upload document files*/
            $filename = '';
            $path = $this->pdfPath;
            //dd($request->get('model_id') );
            if( $request->file() )
                $fileNames = $this->fileUpload($model,$path,$request->file() );
                
               //not summary, it is inhalt + attachment
            
            $counter = 0;
            
            if( isset($fileNames) && count($fileNames) > 0)
                foreach( $fileNames as $fileName ){
                    $counter++;
                    //Editor variant  upload
                    $editorVariantId = EditorVariant::where('document_id',$id)->where('variant_number',$counter)->first();
                    if( $editorVariantId == null)
                        $editorVariantId = new EditorVariant();
                    $editorVariantId->document_id = $id;
                    $editorVariantId->variant_number = $counter;
                    // $editorVariantId->document_status_id = 1;
                    $editorVariantId->inhalt = $request->get('inhalt');
                    $editorVariantId->save();
                    $dirty=$this->dirty($dirty,$editorVariantId);
                    
                    
                    $documentAttachment = new DocumentUpload();
                    $documentAttachment->editor_variant_id = $editorVariantId->id;
                    $documentAttachment->file_path = $fileName;
                    $documentAttachment->save();
                    $dirty=$this->dirty($dirty,$documentAttachment);
                }
            /*end upload files*/
                
                $currDocEv = EditorVariant::find($currentEditorVariant);
                $newAttachment = new EditorVariantDocument();
                $newAttachment->editor_variant_id = $currentEditorVariant;
                $newAttachment->document_id = $document->id;
                $newAttachment->document_status_id = 1;
                $newAttachment->save();
            
            
            $adressats = Adressat::all();
            $docType = DocumentType::find( $request->get('document_type_id') );
            
            
            if($request->has('document_coauthor')){
                $coauthors = $request->input('document_coauthor');
                foreach($coauthors as $coauthor)
                    DocumentCoauthor::create(['document_id'=> $lastId->id, 'user_id'=> $coauthor]);
            }
            
            $backButton = 'dokumente/'.$data->id.'/edit';
            session()->flash('message',trans('documentForm.documentCreateSuccess'));
        }
        
        if($request->has('someMumbe') )
            return redirect('dokumente/rechte-und-freigabe/'.$id );
    
       return redirect()->action('DocumentController@attachments', $id);
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
        $document = Document::find($id);
        if($request->get('roles')!= null && in_array('Alle',$request->get('roles') ) ){
            $document->approval_all_roles = 1; 
            $document->email_approval = $request->get('email_approval');
            $document->save();
            $dirty=$this->dirty(false,$document);
            //Process document approval users
        }
        else{
             $document->approval_all_roles = 0; 
             $document->save();
             $dirty=$this->dirty(false,$document);
        }
        $documentApproval = DocumentApproval::where('document_id',$id)->get();
        $documentApprovalPluck = DocumentApproval::where('document_id',$id)->pluck('user_id');
        //Process document approval users
        
        //do document approvals need soft delete
        $this->document->processOrSave($documentApproval,$documentApprovalPluck,$request->get('approval_users'),
        'DocumentApproval',array('user_id'=>'inherit','document_id'=>$id),array('document_id'=>array($id) ) );
        
        
        //check if has variant
        foreach($request->all() as $k => $v)
            if (strpos($k, 'variante-') !== false){
                $variantNumber = $this->document->variantNumber($k);
                
                if( in_array('Alle',$request->get($k) ) ){
                    $editorVariant = EditorVariant::where('document_id',$id)->where('variant_number',$variantNumber)->first();
                    $editorVariant->approval_all_mandants = 1; 
                    $editorVariant->save();
                    $dirty=$this->dirty($dirty,$editorVariant);
                    // dd($editorVariant);
                }
                else{
                    //editorVariant insert/edit
                    $editorVariant = EditorVariant::where('document_id',$id)->where('variant_number',$variantNumber)->first();
                    $editorVariant->approval_all_mandants = 0; 
                    $editorVariant->save();
                    $dirty=$this->dirty($dirty,$editorVariant);
                    
                    /*Create DocumentManant */
                    $documentMandants = DocumentMandant::where('document_id',$id)->where('editor_variant_id',$editorVariant->id)->get();
                    if( count($documentMandants) < 1){
                        $documentMandant = new DocumentMandant();
                        $documentMandant->document_id = $id;
                        $documentMandant->editor_variant_id = $editorVariant->id;
                        $documentMandant->save();
                        $documentMandants = DocumentMandant::where('document_id',$id)->where('editor_variant_id',$editorVariant->id)->get();
                    } 
                   
                    /*End Create DocumentManant */
                     //dd($documentMandant);
                    
                   
                    /* Create DocumentManant roles*/
                    foreach($documentMandants as $documentMandant){
                        $documentMandantRoles = DocumentMandantRole::where('document_mandant_id',$documentMandant->id)->get();
                        $documentMandantRolesPluck = DocumentMandantRole::where('document_mandant_id',$documentMandant->id)->pluck('role_id');
                        $this->document->processOrSave($documentMandantRoles,$documentMandantRolesPluck,$request->get('roles'), 'DocumentMandantRole',
                            array('document_mandant_id'=>$documentMandant->id,'role_id'=>'inherit'),
                            array('document_mandant_id'=>array($documentMandant->id) ) );
                    }
                    /*End Create DocumentManant roles*/
                    
                    /* Create DocumentManant mandant*/
                    foreach($documentMandants as $documentMandant){
                        
                        $documentMandantMandats = DocumentMandantMandant::where('document_mandant_id',$documentMandant->id)->get();
                        $documentMandantMandatsPluck = DocumentMandantMandant::where('document_mandant_id',$documentMandant->id)->pluck('mandant_id');
                        
                        //dd($request->get($k));
                        //INSERTS LAST VALUES->check foreach!
                        $this->document->processOrSave($documentMandantMandats,$documentMandantMandatsPluck,$request->get($k), 'DocumentMandantMandant',
                            array('document_mandant_id'=>$documentMandant->id,'mandant_id'=>'inherit'),
                            array('document_mandant_id'=>array($documentMandant->id)  ), true  );
                    }
                    /*End Create DocumentManant mandant*/
                    
                }//end else
            }
        
        $document = Document::find($id); 
        if( $request->has('email_approval') )
            $document->email_approval = 1;
            
        
        if( $request->has('fast_publish') ){
            
            //save to Published documents
            $document->document_status_id = 2;
            
            
            
            $document->save();
            $dirty=$this->dirty($dirty,$document);
            if($dirty == true)
                session()->flash('message',trans('documentForm.fastPublished'));
            return redirect('/');
        }
        elseif( $request->has('ask_publishers') ){
            $document->document_status_id = 2;
            
            //if send email-> send emails || messages
            
            $document->save();
            $dirty=$this->dirty($dirty,$document);
            if($dirty == true)
             session()->flash('message',trans('documentForm.askPublishers'));
            return redirect('/');
        }
        else{
            //just refresh the page 
            $document->save();
            $dirty=$this->dirty($dirty,$document);
            if($dirty == true)
                session()->flash('message',trans('documentForm.saved'));
           return redirect('dokumente/rechte-und-freigabe/'.$id );        
        }
           
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $document = Document::find($id);
        return view('dokumente.show', compact('document') );
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

        // if(isset($data->date_expired) && $data->date_expired !=null)
        //     $data->date_expired = Carbon::parse($data->date_expired)->format('d.m.Y.');
        
        $url = 'PATCH';
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->first()->mandant_id;
        $url = '';
        $documentCoauthor = DocumentCoauthor::where('document_id', $id)->get();
        $documentTypes = DocumentType::all();
        $isoDocuments = IsoCategory::all();
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
        
        $mandantUsers = User::leftJoin('mandant_users', 'users.id', '=', 'mandant_users.user_id')
        ->where('mandant_id', $mandantId)
        ->whereNotIn('users.id',array(Auth::user()->id) )->get();
        // session()->flash('message',trans('documentForm.documentEditSuccess'));
         
        return view('formWrapper', compact('data','method','url','documentTypes','isoDocuments','mandantUsers', 'documentCoauthor') );
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
        
        DocumentCoauthor::where('document_id', $id)->delete();
        if($request->has('document_coauthor')){
            $coauthors = $request->input('document_coauthor');
            foreach($coauthors as $coauthor)
                DocumentCoauthor::create(['document_id'=> $id, 'user_id'=> $coauthor]);
        }
        
        $backButton = url('dokumente/'.$data->id.'/edit');
        // session()->flash('message',trans('documentForm.documentCreateSuccess'));
        return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats') );
        //->with('message', trans('documentForm.documentCreateSuccess'));
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
        $rundschreibenAll = $this->document->generateTreeview(Document::where(['document_type_id' => 3])->orderBy('id', 'desc')->take(50)->get());
        $rundschreibenMeine = $this->document->generateTreeview(Document::where(['user_id' => Auth::user()->id, 'document_type_id' => 3])->orderBy('id', 'desc')->take(10)->get());
        
        return view('dokumente.rundschreiben', compact('rundschreibenAll','rundschreibenMeine') );
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
     * @return array $uploadedNames
     */
    public function documentHistory($id)
    {
        $variants = $this->document->generateDummyData('Anhang');
        $documents = $this->document->generateDummyData('Variante', $variants);
        $history = $this->document->generateDummyData('Dokument', $documents);
        $data = json_encode($history);
        return view('dokumente.historie', compact('data') );
    }
    
     /**
     * Process files for upload
     *
     * @param DB Object(collection) $model
     * @param string $path
     * @param array $files
     * @return \Illuminate\Http\Response
     */
    private function fileUpload($model,$path,$files){
      
        $folder = $this->pdfPath.str_slug($model->name);
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
    
    /**
     * Move files from temp folder and rename them
     *
     * @param file object $file
     * @param string $folder
     * @param DB object(collection) $model
     * @return string $newName
     */
    private function moveUploaded($file,$folder,$model){
        //$filename = $image->getClientOriginalName();
		$newName = str_slug($model->name).'-'.date("d-m-Y-H:i:s").'-'.substr(time(),0,4).'.'.$file->getClientOriginalExtension();
		$path = "$folder/$newName";
// 		dd($path);
        $filename = $file->getClientOriginalName();
        $uploadSuccess = $file->move($folder, $newName );
      	\File::delete($folder .'/'. $filename);
      	return $newName;
    }
    
    
    /**
     * Next form checker for 
     *
     * @return NON
     */
    private function nextFormChecker($model,$linkMe=''){
        
    }
    
    /**
     * Display the documents for the specified ISO category slug.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function isoCategoriesBySlug($slug)
    {
        
        $documentsAll = Document::all();
        $documentsIso = array();
        $documentsBySlug = array();
        
        foreach ($documentsAll as $document) {
            if($document->document_type_id == 4)
                $documentsIso[] = $document;
        }
        
        // dd($documentsIso);
        
        foreach ($documentsIso as $document) {
            if($document->isoCategories()->where('slug', $slug)->first())
                $documentsBySlug[] = $document;
        }
        
        if(count($documentsIso))
        $isoDocumentsAll = $this->document->generateTreeview($documentsIso);
        else $isoDocumentsAll = null;
        
        if(count($documentsBySlug))
        $isoDocumentsBySlug = $this->document->generateTreeview($documentsBySlug);
        else $isoDocumentsBySlug = null;
        
        
        return view('dokumente.isoDocument', compact('isoDocumentsAll', 'isoDocumentsBySlug') );
        
    }
    
    /**
     * Set back button
     *
     * @param file object $file
     * @param string $folder
     * @param DB object(collection) $model
     * @return string $newName
     */
    private function setBackButton($id,$attachment=false){
        $docType= $this->detectDocumentType($id);
        if( $attachment == true ){
          
        }
       
        
       
        return $newName;
    }
    
    /**
     * detect document type
     *
     * @param file object $file
     * @param string $folder
     * @param DB object(collection) $model
     * @return string $newName
     */
    private function detectDocumentType($id){
        $document =  Document::find($id);
        if( $document->pdf_upload == true )
            return 'pdf';
        elseif( $document->document_type_id == 5 )
            return 'upload';
        
        return 'editor';
       
    }
    
    /**
     * detect if model is dirty or not
     * @return bool 
     */
    private function dirty($dirty,$model){
       
        if( $model->isDirty() || $dirty == true )
            return true;
        return false;
       
    }
    
    
}
