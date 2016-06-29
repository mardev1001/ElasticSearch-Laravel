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
use App\DocumentStatus;
use App\DocumentComment;
use App\PublishedDocument;
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
        $this->movePath = public_path().'/files/documents';
        $this->pdfPath = public_path().'/files/documents/';
        $this->newsId = 1;
        $this->rundId = 2;
        $this->qmRundId = 3;
        $this->isoDocumentId = 4;
        $this->formulareId = 5;
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
        $documentStatus = DocumentStatus::all();
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
       
        $mandantUsers = User::leftJoin('mandant_users', 'users.id', '=', 'mandant_users.user_id')
        ->where('mandant_id', $mandantId)->get();
        // dd($mandantUsers);
         
        return view('formWrapper', compact('url', 'documentTypes', 'isoDocuments','documentStatus', 'mandantUsers', 'documentCoauthors') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $adressats = Adressat::where('active',1)->get();
        //DB::enableQueryLog();
        $docType = DocumentType::find( $request->get('document_type_id') );
        
        //fix if document type not iso category -> don't save iso_category_id
        if( $request->get('document_type_id') != $this->isoDocumentId )
            RequestMerge::merge(['iso_category_id' => null] );
            
        if( $request->get('document_type_id') != $this->newsId && $request->get('document_type_id') !=  $this->rundId
            && $request->get('document_type_id') != $this->qmRundId && $request->has('pdf_upload') )
            RequestMerge::merge(['pdf_upload' => 0] );    
    
            
        RequestMerge::merge(['version' => 1] );
        $setDocument = $this->document->setDocumentForm($request->get('document_type_id'), $request->get('pdf_upload')  );
        
        $data = Document::create( $request->all() );
       
        $lastId = Document::orderBy('id','DESC')->first();
        $lastId->document_group_id = $lastId->id;
        $lastId->save();
        
        if($request->has('document_coauthor') && $request->input('document_coauthor')[0] != "0" ){
            $coauthors = $request->input('document_coauthor');
            foreach($coauthors as $coauthor)
                if( $coauthor != '0');
                    DocumentCoauthor::create(['document_id'=> $lastId->id, 'user_id'=> $coauthor]);
        }

        $url = $setDocument->url;
        $form = $setDocument->form;
        $backButton = '/dokumente/'.$data->id.'/edit';
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
        if($request->has('betreff')){
            $model->betreff = $request->get('betreff');
            $dirty=$this->dirty($dirty,$model);
            $model->save();
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
         RequestMerge::merge(['pdf_upload' => 1/*maybe auto*/] );
        $data = Document::findOrNew($request->get('model_id'));
        
        $data->fill($request->all() );
        $dirty=$this->dirty($dirty,$data);
        $data->save();
        $id = $data->id; 
      
        RequestMerge::merge(['document_id' => $id,'variant_number' => 1/*maybe auto*/] );
        //$inputs = $request->except(array('_token', '_method','save','next') );
        $editorVariantId = EditorVariant::where('document_id',$id)->first();
        if( $editorVariantId == null)
            $editorVariantId = new EditorVariant();
        $editorVariantId->document_id = $id;
        $editorVariantId->variant_number = 1;
        $editorVariantId->inhalt = $request->get('inhalt');
        $dirty=$this->dirty($dirty,$editorVariantId);
        $editorVariantId->save();
        $editorVariantId::where('document_id',$id)->first();
        
        if(count($fileNames) > 0 ){
            foreach( $fileNames as $fileName ){
                $documentAttachment = new DocumentUpload();
                $documentAttachment->editor_variant_id = $editorVariantId->id;
                $documentAttachment->file_path = $fileName;
                $documentAttachment->save();
                $dirty = true;
            }
        }
        if($dirty == true)
         session()->flash('message',trans('documentForm.documentPdfCreateSuccess'));
        if( $request->has('save') ){
            $adressats = Adressat::where('active',1)->get();
            $setDocument = $this->document->setDocumentForm( $data->document_type_id, $data->pdf_upload  );
            $url = $setDocument->url;
            $form = $setDocument->form;
            $backButton = '/dokumente/'.$data->id.'/edit';
            return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats') );
        }
        $backButton = '/dokumente/pdf-upload/'.$data->id.'/edit';
       
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
        $adressats = Adressat::where('active',1)->get();
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
        $dirty=$this->dirty(false,$data);
        $data->save();
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
                $dirty=$this->dirty($dirty,$editorVariantId);
                $editorVariantId->save();
                //Upload documents
                
                
                $documentAttachment = new DocumentUpload();
                $documentAttachment->editor_variant_id = $editorVariantId->id;
                $documentAttachment->file_path = $fileName;
                $dirty=$this->dirty($dirty,$documentAttachment);
                $documentAttachment->save();
            }
        if($dirty == true)
            session()->flash('message',trans('documentForm.documentUploadedCreateSuccess'));
        if( $request->has('save') ){
            $adressats = Adressat::where('active',1)->get();
            $setDocument = $this->document->setDocumentForm( $data->document_type_id, $data->pdf_upload  );
            $url = $setDocument->url;
            $form = $setDocument->form;
            $backButton = '/dokumente/'.$data->id.'/edit';
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
    public function editDocumentUpload($id)
    {
        $adressats = Adressat::where('active',1)->get();
        $data = Document::find($id);
        $backButton = '/dokumente/'.$data->id.'/edit';
        $setDocument = $this->document->setDocumentForm($data->document_type_id, $data->pdf_upload );
        $url = $setDocument->url;
        $form = $setDocument->form;
        return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats') );
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function documentEditor(Request $request)
    {
        
        $model = Document::find($request->get('model_id'));
        if($model == null){
            
            return redirect('dokumente/create')->with( array('message'=>'No Document with that id') );
        }
        
        $data = Document::findOrNew($request->get('model_id'));
        $data->fill($request->all() );
        $data->save();
        $dirty=$this->dirty(false ,$data);
        $id = $data->id; 
        
         if($request->has('betreff')){
            $model->betreff = $request->get('betreff');
            $dirty=$this->dirty($dirty,$model);
            $model->save();
        }
        
        $savedVariantIds= array();
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
                $dirty=$this->dirty($dirty,$editorVariant);
                $editorVariant->save();
                $savedVariantIds[] = $editorVariant->variant_number;
            }
            
            /*If some variant are removed*/
             $removeEditors = EditorVariant::where('document_id',$id)->whereNotIn('variant_number',$savedVariantIds)->get();
            //  dd($removeEditors);
            foreach( $removeEditors as $editor){
                if($editor->deleted_at == null)
                    $editor->delete();
            }
            
            /*end some variant are removed*/
         if($dirty == true)
            session()->flash('message',trans('documentForm.documentEditorCreateSuccess'));
        
        if( $request->has('save') ){
            $adressats = Adressat::where('active',1)->get();
            $setDocument = $this->document->setDocumentForm( $data->document_type_id, $data->pdf_upload  );
            $url = $setDocument->url;
            $form = $setDocument->form;
            $backButton = '/dokumente/'.$data->id.'/edit';
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
        $adressats = Adressat::where('active',1)->get();
        $data = Document::find($id);
        $backButton = '/dokumente/'.$data->id.'/edit';
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
        $dt = DocumentType::find($this->formulareId);//vorlage document
         
        $backButton = '/dokumente/editor/'.$data->id.'/edit';
        if( ($data->document_type_id ==  $this->newsId && $data->pdf_upload == true ) || ($data->document_type_id == $this->rundId 
        && $data->pdf_upload == true ) || ($data->document_type_id == $this->qmRundId && $data->pdf_upload == true ))
            $backButton = '/dokumente/pdf-upload/'.$id.'/edit';
        elseif($data->document_type_id ==  $dt->id)    
            $backButton = '/dokumente/dokumente-upload/'.$id.'/edit';  
            
        $nextButton = '/dokumente/rechte-und-freigabe/'.$data->id;
        $url = '';
        $documents = Document::where('document_type_id',$dt->id)->where('document_status_id',1)->orWhere('document_status_id',3)->whereNotIn('id',array($id))->get();// documentTypeId 5 = Vorlagedokument
        foreach($documents as $document){
            $document->name = $document->name.' ('.$document->documentStatus->name.')';    
        }
        
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->first()->mandant_id;
        $attachmentArray = array();
        /*Check if document has editorVariant*/
        if( count( $data->editorVariant) > 0 )
            foreach( $data->editorVariant as $variant){
                $attachmentArray[$variant->id] = $this->document->generateTreeview($variant,false,false,$id);
            }
        /*End Check if document has attachments*/
        
        $url = '';
        $documentTypes = DocumentType::all();
        $isoDocuments = IsoCategory::all();
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
       
        $mandantUsers = User::leftJoin('mandant_users', 'users.id', '=', 'mandant_users.user_id')
        ->where('mandant_id', $mandantId)->get();
         
        $collections = array();
        $roles = Role::all();
        //find variants
        $variants = EditorVariant::where('document_id',$data->id)->get();
        $documentStatus = DocumentStatus::all();
        
        return view('dokumente.attachments', compact('collections','data','attachmentArray','documents','documentStatus','url','documentTypes',
        'isoDocuments','mandantUsers','backButton','nextButton') );
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
        $data = Document::find($id);
        
        $currentEditorVariant = $request->get('variant_id');
        $document = Document::find($request->get('document_id'));
        /*If option 1*/
            if($request->has('attach')){
                $currentDocumentLast =$data->lastEditorVariant[0];
                    /*
                        Opcija 1 razložena
                        za svaki document upload od drugog documenta
                        dodaj i editor_variant_documents --->Kako razlikovat attachmente od dokumenata ako je ista varijanta?
                        document_id u editor_variant_documents je foreign key!
                        i documentUploads uzmi path od drugog dokumenta i stavi editor_variant_id od trenutnog
                    */
                    $currDocEv = EditorVariant::find($currentEditorVariant);
                    $documentCheck = EditorVariantDocument::where('editor_variant_id',$currentEditorVariant)->where('document_id',$document->id)->count();
                  
                    if( $documentCheck < 1){
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
                Option 2: create a new Vorlagedokument and add it as an attachment
            */
            $dt = DocumentType::find(  $this->formulareId = $this->formulareId);//vorlage document
            RequestMerge::merge(['version' => 1, 'document_type_id' => $dt->id,'is_attachment'=> 1] );
            
            
            if($request->has('document_coauthor') && $request->input('document_coauthor')[0] != "0" ){
                $coauthors = $request->input('document_coauthor');
                foreach($coauthors as $coauthor)
                    if( $coauthor != '0');
                        DocumentCoauthor::create(['document_id'=> $lastId->id, 'user_id'=> $coauthor]);
            }
          
            
        
            /*Create a new document*/
            $data = Document::create($request->all() );
            $lastId = Document::orderBy('id','DESC')->first();
            $lastId->document_group_id = $lastId->id;
            $lastId->save();
            
            
            /*Upload document files*/
            $filename = '';
            $path = $this->pdfPath;
            if( $request->file() )
                 $fileNames = $this->fileUpload($lastId,$path,$request->file() );
                
               //not summary, it is inhalt + attachment
            
            $counter = 0;
            if( isset($fileNames) && count($fileNames) > 0)
                foreach( $fileNames as $fileName ){
                    $counter++;
                    //Editor variant  upload
                    $editorVariantId = new EditorVariant();
                    $editorVariantId->document_id = $lastId->id;
                    $editorVariantId->variant_number = $counter;
                    $editorVariantId->inhalt = $request->get('inhalt');
                    $dirty=$this->dirty(false,$editorVariantId);
                    $editorVariantId->save();
                    
                    $documentAttachment = new DocumentUpload();
                    $documentAttachment->editor_variant_id = $editorVariantId->id;
                    $documentAttachment->file_path = $fileName;
                    $dirty=$this->dirty($dirty,$documentAttachment);
                    $documentAttachment->save();
                
                }
                /*end upload files*/
                
                $currDocEv = EditorVariant::find($currentEditorVariant);
                $newAttachment = new EditorVariantDocument();
                $newAttachment->editor_variant_id = $currentEditorVariant;
                $newAttachment->document_id = $lastId->id;
                $newAttachment->document_status_id = 1;
                $newAttachment->save();
                
               
            
            
            $adressats = Adressat::where('active',1)->get();
            $docType = DocumentType::find( $request->get('document_type_id') );
            
            
            
            
            $backButton = '/dokumente/'.$data->id.'/edit';
            
             $currDocEv = EditorVariant::find($currentEditorVariant);
                    
                    $newAttachment = new EditorVariantDocument();
                    $newAttachment->editor_variant_id = $currentEditorVariant;
                    $newAttachment->document_id = $document->id;
                    $newAttachment->document_status_id = 1;
                    $newAttachment->save();
            
        }
        
        if($request->has('next') )
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
        $dt = DocumentType::find($this->formulareId);//vorlage document
        
        $backButton = '/dokumente/editor/'.$data->id.'/edit';
        if( ($data->document_type_id == $this->rundId  && $data->pdf_upload == true ) || ($data->document_type_id == $this->qmRundId && $data->pdf_upload == true ) 
        || ($data->document_type_id == $this->newsId && $data->pdf_upload == true ))
            $backButton = '/dokumente/pdf-upload/'.$id.'/edit';
       
        elseif($data->document_type_id ==  $dt->id)    
            $backButton = '/dokumente/dokumente-upload/'.$id.'/edit';  
        
        if($data->pdf_upload == true || $data->pdf_upload==1)
            $backButton = '/dokumente/pdf-upload/'.$data->id.'/edit'; 
        elseif( $data->pdf_upload == false &&  $data->document_type_id == $this->formulareId )  
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
        // dd($request->all());
        $document = Document::find($id);
        if($request->get('roles')!= null && in_array('Alle',$request->get('roles') ) ){
            $document->approval_all_roles = 1; 
            $document->email_approval = $request->get('email_approval');
            $dirty=$this->dirty(false,$document);
            $document->save();
            //Process document approval users
        }
        else{
             $document->approval_all_roles = 0; 
             $dirty=$this->dirty(false,$document);
             $document->save();
        }
        
        $documentApproval = DocumentApproval::where('document_id',$id)->get();
        $documentApprovalPluck = DocumentApproval::where('document_id',$id)->pluck('user_id');
        //Process document approval users
        
        //do document approvals need soft delete
        if( !empty( $request->get('approval_users') ) )
            $this->document->processOrSave($documentApproval,$documentApprovalPluck,$request->get('approval_users'),
            'DocumentApproval',array('user_id'=>'inherit','document_id'=>$id),array('document_id'=>array($id) ) );
        else
            $documentApproval = DocumentApproval::where('document_id',$id)->delete();
        
        //check if has variant
        $hasVariants = false;
        /* If variants exist in request */
        foreach($request->all() as $k => $v){
            /* If Variants are not empty */
            if (strpos($k, 'variante-') !== false && !empty($v) ){
                $hasVariants = true;
                $variantNumber = $this->document->variantNumber($k);
                
                if( in_array('Alle',$request->get($k) ) ){
                    $editorVariant = EditorVariant::where('document_id',$id)->where('variant_number',$variantNumber)->first();
                    $editorVariant->approval_all_mandants = 1; 
                    $dirty=$this->dirty($dirty,$editorVariant);
                    $editorVariant->save();
                    
                    /*Fix where where variant is Alle and roles different from All*/
                    $documentMandants = DocumentMandant::where('document_id',$id)->where('editor_variant_id',$editorVariant->id)->get();
                     foreach($documentMandants as $documentMandant){
                        $documentMandantRoles = DocumentMandantRole::where('document_mandant_id',$documentMandant->id)->get();
                        $documentMandantRolesPluck = DocumentMandantRole::where('document_mandant_id',$documentMandant->id)->pluck('role_id');
                        
                        if( $request->has('roles') )
                            $this->document->processOrSave($documentMandantRoles,$documentMandantRolesPluck,$request->get('roles'), 'DocumentMandantRole',
                                array('document_mandant_id'=>$documentMandant->id,'role_id'=>'inherit'),
                                array('document_mandant_id'=>array($documentMandant->id) ) ); 
                        
                        elseif( !$request->has('roles') ){
                             $documentMandantRoles = DocumentMandantRole::where('document_mandant_id',$documentMandant->id)->delete();
                        }
                     }//end foreach
                     
                     /* End Fix where where variant is Alle and roles different from All*/
                }
                else{
                    //editorVariant insert/edit
                    $editorVariant = EditorVariant::where('document_id',$id)->where('variant_number',$variantNumber)->first();
                    $editorVariant->approval_all_mandants = 0; 
                    $dirty=$this->dirty($dirty,$editorVariant);
                    $editorVariant->save();
                    
                    /*Create DocumentManant */
                    $documentMandants = DocumentMandant::where('document_id',$id)->where('editor_variant_id',$editorVariant->id)->get();
                    
                    if( count($documentMandants) < 1){
                        $documentMandant = new DocumentMandant();
                        $documentMandant->document_id = $id;
                        $documentMandant->editor_variant_id = $editorVariant->id;
                        $dirty=$this->dirty($dirty,$documentMandant);
                        $documentMandant->save();
                        $documentMandants = DocumentMandant::where('document_id',$id)->where('editor_variant_id',$editorVariant->id)->get();
                    } 
                   /*End Create DocumentManant */
                   
                      
                    /* Create DocumentManant roles*/
                    foreach($documentMandants as $documentMandant){
                        $documentMandantRoles = DocumentMandantRole::where('document_mandant_id',$documentMandant->id)->get();
                        $documentMandantRolesPluck = DocumentMandantRole::where('document_mandant_id',$documentMandant->id)->pluck('role_id');
                       
                       if( $request->has('roles') )
                        $this->document->processOrSave($documentMandantRoles,$documentMandantRolesPluck,$request->get('roles'), 'DocumentMandantRole',
                            array('document_mandant_id'=>$documentMandant->id,'role_id'=>'inherit'),
                            array('document_mandant_id'=>array($documentMandant->id) ) ); 
                        
                        elseif( !$request->has('roles') ){
                             $documentMandantRoles = DocumentMandantRole::where('document_mandant_id',$documentMandant->id)->delete();
                        }
                           
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
        // dd($request->all());
        }
        /* End If variants exist in request */
        
        //fix when there are roles set, but no variants
        if( $hasVariants == false && $request->has('roles')){
          
            $editorVariantsNumbers = EditorVariant::where('document_id',$id)->get();
            
            foreach($editorVariantsNumbers as $editorVariant){
                $variantNumber = $editorVariant->variant_number;
                
                    $editorVariant->approval_all_mandants = 0; 
                    $dirty=$this->dirty($dirty,$editorVariant);
                    $editorVariant->save();
                    
                    /*Create DocumentManant */
                    $documentMandants = DocumentMandant::where('document_id',$id)->where('editor_variant_id',$editorVariant->id)->get();
                    
                    if( count($documentMandants) < 1){
                        $documentMandant = new DocumentMandant();
                        $documentMandant->document_id = $id;
                        $documentMandant->editor_variant_id = $editorVariant->id;
                        $dirty=$this->dirty($dirty,$documentMandant);
                        $documentMandant->save();
                        $documentMandants = DocumentMandant::where('document_id',$id)->where('editor_variant_id',$editorVariant->id)->get();
                    } 
                   /*End Create DocumentManant */
                   
                    
                    /* Create DocumentManant roles*/
                    foreach($documentMandants as $documentMandant){
                        $documentMandantRoles = DocumentMandantRole::where('document_mandant_id',$documentMandant->id)->get();
                        $documentMandantRolesPluck = DocumentMandantRole::where('document_mandant_id',$documentMandant->id)->pluck('role_id');
                        
                       
                        $this->document->processOrSave($documentMandantRoles,$documentMandantRolesPluck,$request->get('roles'), 'DocumentMandantRole',
                            array('document_mandant_id'=>$documentMandant->id,'role_id'=>'inherit'),
                            array('document_mandant_id'=>array($documentMandant->id) ) );
                    }
                    /*End Create DocumentManant roles*/
                  
                    /* Delete variant mandants*/     
                        $documentMandantMandats = DocumentMandantMandant::where('document_mandant_id',$documentMandant->id)->delete();
                    /* End Delete variant mandants*/                  
                   
            }
        }//end has variants false and has roles
        
        /*fix where roles aren't set and variants aren't set*/
        else if( $hasVariants == false && !$request->has('roles')){
             $editorVariantsNumbers = EditorVariant::where('document_id',$id)->get();
            
            foreach($editorVariantsNumbers as $editorVariant){
                $variantNumber = $editorVariant->variant_number;
                
                    $editorVariant->approval_all_mandants = 0; 
                    $dirty=$this->dirty($dirty,$editorVariant);
                    $editorVariant->save();
                    
                    /*Create DocumentManant */
                    $documentMandants = DocumentMandant::where('document_id',$id)->where('editor_variant_id',$editorVariant->id)->get();
                    
                    if( count($documentMandants) < 1){
                        $documentMandant = new DocumentMandant();
                        $documentMandant->document_id = $id;
                        $documentMandant->editor_variant_id = $editorVariant->id;
                        $dirty=$this->dirty($dirty,$documentMandant);
                        $documentMandant->save();
                        $documentMandants = DocumentMandant::where('document_id',$id)->where('editor_variant_id',$editorVariant->id)->get();
                    } 
                   /*End Create DocumentManant */
                   
                    
                    /* Delete DocumentManant roles*/
                    foreach($documentMandants as $documentMandant){
                        $documentMandantRoles = DocumentMandantRole::where('document_mandant_id',$documentMandant->id)->delete();
                        
                    /*End Delete DocumentManant roles*/
                  
                    /* Delete variant mandants*/     
                        $documentMandantMandats = DocumentMandantMandant::where('document_mandant_id',$documentMandant->id)->delete();
                    /* End Delete variant mandants*/                  
                    }
                   
            }
        }
        /* End fix where roles aren't set and variants aren't set*/
        
        $document = Document::find($id); 
        if( $request->has('email_approval') )
            $document->email_approval = 1;
            
        
        if( $request->has('fast_publish') ){
          
            //save to Published documents
            $document->document_status_id = 3;//aktualan
            $dirty=$this->dirty($dirty,$document);
            $document->save();
            
            if($dirty == true)
                session()->flash('message',trans('documentForm.fastPublished'));
            return redirect('/');
        }
        elseif( $request->has('ask_publishers') ){
            $document->document_status_id = 6;
            
            //if send email-> send emails || messages
            $dirty=$this->dirty($dirty,$document);
            $document->save();
            
            if( $request->has('approval_users') ){
                $approvals = DocumentApproval::where('document_id',$id)->delete();
                foreach( $request->get('approval_users') as $approvalUser ){
                    $approvalUser = DocumentApproval::create( array('document_id'=>$id,'user_id'=>$approvalUser ) );
                    $dirty = true;
                }
            }
            
            if($dirty == true)
                session()->flash('message',trans('documentForm.askPublishers'));
            return redirect('/');
        }
        else{
            //just refresh the page 
            $dirty=$this->dirty($dirty,$document);
            $document->save();
            
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
        $documentComments = DocumentComment::where('document_id',$id)->get();
        return view('dokumente.show', compact('document','documentComments') );
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

        $url = 'PATCH';
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->first()->mandant_id;
        $url = '';
        $documentCoauthor = DocumentCoauthor::where('document_id', $id)->get();
        $documentTypes = DocumentType::all();
        $isoDocuments = IsoCategory::all();
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
        $documentStatus = DocumentStatus::all();
        $mandantUsers = User::leftJoin('mandant_users', 'users.id', '=', 'mandant_users.user_id')
        ->where('mandant_id', $mandantId)->get();
        // session()->flash('message',trans('documentForm.documentEditSuccess'));
         
        return view('formWrapper', compact('data','method','url','documentTypes','isoDocuments','documentStatus','mandantUsers', 'documentCoauthor') );
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
        $adressats = Adressat::where('active',1)->get();
        
        //fix if document type not iso category -> don't save iso_category_id
        if( $request->get('document_type_id') !=  $this->isoDocumentId )
            RequestMerge::merge(['iso_category_id' => null] );
            
        if( $request->get('document_type_id') != $this->newsId && $request->get('document_type_id') !=  $this->rundId
            && $request->get('document_type_id') !=  $this->qmRundId  && $request->has('pdf_upload') )
            RequestMerge::merge(['pdf_upload' => 0] );    
    
        // dd($request->all());        
        RequestMerge::merge(['version' => 1] );
        $data = Document::find( $id );
        $prevName = $data->name;
        if( $request->get('name') != $prevName ){
            $oldSlug= str_slug($prevName);
            $newSlug= str_slug($request->get('name'));
            // Storage::move('hodor/oldfile-name.jpg', 'hodor/newfile-name.jpg'); // keep the same folder to just rename 
            foreach($data->documentUploads as $upload){
                $oldPath = $upload->file_path;
                $newFileName = str_replace($oldSlug,$newSlug, $upload->file_path);
               
               if ( ! \File::exists( $this->movePath.'/'.$newSlug.'/' ) ) {
        			\File::makeDirectory( $this->movePath.'/'.$newSlug.'/', $mod=0777,true,true); 
        		}
               copy($this->movePath.'/'.$oldSlug.'/'.$oldPath, $this->movePath.'/'.$newSlug.'/'.$newFileName);
               $upload->file_path = $newFileName;
               $upload->save();
            }
            //rename folder to new name
            //rename files to new name
            //rename documentUploads
            $dirname= $this->movePath.'/'.$oldSlug;
              if (is_dir($dirname))
           $dir_handle = opendir($dirname);
        	 if (!$dir_handle)
        	      return false;
        	 while($file = readdir($dir_handle)) {
        	       if ($file != "." && $file != "..") {
        	            if (!is_dir($dirname."/".$file))
        	                 unlink($dirname."/".$file);
        	            else
        	                 delete_directory($dirname.'/'.$file);
        	       }
        	 }
        	 closedir($dir_handle);
        	 rmdir($dirname);
        }
            
        $data->fill( $request->all() )->save();
        $data = Document::find($id);
        
        
        $variant = $data->editorVariant();
        $setDocument = $this->document->setDocumentForm($request->get('document_type_id'), $request->get('pdf_upload')  );
        $url = $setDocument->url;
        $form = $setDocument->form;
        
        DocumentCoauthor::where('document_id', $id)->delete();
        if($request->has('document_coauthor') && $request->input('document_coauthor')[0] != "0" ){
            $coauthors = $request->input('document_coauthor');
            foreach($coauthors as $coauthor)
                DocumentCoauthor::create(['document_id'=> $id, 'user_id'=> $coauthor]);
        }
        
        $backButton = url('/dokumente/'.$data->id.'/edit');
        // session()->flash('message',trans('documentForm.documentCreateSuccess'));
        return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats') );
        //->with('message', trans('documentForm.documentCreateSuccess'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function newVersion($id)
    // {
    //     //find if document has version higher than one
    //     $version = 0;
    //     $document = Document::find($id);
    //     $version = $document->version;
    //     if( $document->document_group_id == null || $document->document_group_id == 0){
    //         $document->document_group_id = $document->id;
    //         $document->save();
    //     }
    //     else{
    //         $highestVersion = Document::where('document_group_id',$document->document_group_id)->orderBy('version','DESC')->first();
    //         $version = $highestVersion->version;
    //     }
    //     /*Set all previous versions to arhived*/
    //     /*End Set all previous versions to arhived*/
    //     $newDocument = $document->replicate();
    //     $newDocument->version = $version+1;
    //     $newDocument->version_parent = $version;
    //     $newDocument->document_status_id = 1;
    //     $newDocument->date_expired = null;
    //     $newDocument->date_published = null;
    //     $newDocument->date_approved = null;
    //     $newDocument->save();
    //     // dd($newDocument);
        
    //     /*Duplicate document variants*/
    //     foreach($document->editorVariant as $variant){
    //         $newVariant = $variant->replicate();
    //         $newVariant->document_id = $newDocument->id;
    //         $newVariant->save();
            
    //         /*Duplicate document uploads*/
    //         foreach( $variant->documentUpload as $upload){
    //             $newUpload = $upload->replicate();
    //             $newUpload->editor_variant_id = $newVariant->id;
    //             $newUpload->save();
    //         }
    //         /*End Duplicate document uploads*/
            
    //         /*Duplicate editor_variant_documents*/
    //         foreach( $variant->editorVariantDocument as $editorVariantDocument){
    //             $newEditorVariantDocument = $editorVariantDocument->replicate();
    //             $newEditorVariantDocument->editor_variant_id = $newVariant->id;
    //             $newEditorVariantDocument->document_status_id = $newDocument->document_status_id;
    //             $newEditorVariantDocument->document_group_id = $newDocument->document_group_id;
    //             $newEditorVariantDocument->document_id = $newDocument->id;
    //             $newEditorVariantDocument->save();
    //         }
    //         /*End Duplicate editor_variant_documents*/
            
    //         /*Duplicate document mandants*/
    //         foreach( $variant->documentMandants as $documentMandant){
    //             $newDocumentMandant = $documentMandant->replicate();
    //             $newDocumentMandant->document_id = $newDocument->id;
    //             $newDocumentMandant->editor_variant_id = $newVariant->id;
    //             $newDocumentMandant->save();
                
    //             /*Duplicate document mandant mandants*/
    //             foreach($documentMandant->documentMandantMandants as $docMandantMandant){
    //                 $newDMM = $docMandantMandant->replicate();
    //                 $newDMM->document_mandant_id = $newDocumentMandant->id;
    //                 $newDMM->save();
    //             }
    //             /*End Duplicate document mandant mandants*/
                
    //             /*Duplicate document mandant roles*/
    //              foreach($documentMandant->documentMandantRole as $docMandantRole){
    //                 $newDMR = $docMandantRole->replicate();
    //                 $newDMR->document_mandant_id = $newDocumentMandant->id;
    //                 $newDMR->save();
    //             }
    //             /*End Duplicate document mandant roles*/
    //         }   
    //         /*End Duplicate  document mandants*/
    //     }
    //     /* End Duplicate document variants*/
    //     // dd($newDocument);
    //      session()->flash('message',trans('documentForm.newVersionSuccess'));
    //     return redirect('dokumente/'.$newDocument->id.'/edit' );
        
    // }
    
    

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function saveComment(Request $request, $id)
    {
        RequestMerge::merge(['document_id'=>$id,'user_id' => Auth::user()->id,'active' => null,'freigeber'=>null] );
        // dd( $request->all() );
        $comment =  DocumentComment::create( $request->all() );
        
        session()->flash('message',trans('documentForm.savedComment'));
        
        return redirect('dokumente/'.$id);
       
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function authorizeDocument($id)
    {
        $document = Document::find($id);
        $user = Auth::user()->id;
        $document->document_status_id = 3; 
        $document->save();
        $continue = true;
        $uniqeUrl = '';
        while ($continue) {
            $uniqeUrl = $this->generateUniqeLink();
            if (PublishedDocument::where('url_unique',$uniqeUrl)->count() != 1)
                $continue = false;
        }
        $documentApproval = DocumentApproval::where('document_id', $id)->where('user_id',$user)->first();
        $documentApproval->approved = 1;
        $documentApproval->date_approved = Carbon::now();
        $documentApproval->save();
        $publishedDocs = PublishedDocument::create(['document_id'=> $id, 'document_group_id' => $document->document_group_id,'url_unique'=>$uniqeUrl]);
        session()->flash('message',trans('documentForm.authorized'));
        return redirect('/');
    }
    
     /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rundschreiben()
    {
        $rundschreibenAll = Document::where(['document_type_id' =>  $this->rundId])->orderBy('id', 'desc')->paginate(10, ['*'], 'alle-rundschreiben');
        $rundschreibenAllTree = $this->document->generateTreeview( $rundschreibenAll );
        $rundschreibenMeine = Document::where(['user_id' => Auth::user()->id, 'document_type_id' =>  $this->rundId])->orderBy('id', 'desc')->take(10)->paginate(10, ['*'], 'meine-rundschreiben');
        $rundschreibenMeineTree = $this->document->generateTreeview( $rundschreibenMeine );
        
        return view('dokumente.rundschreiben', compact('rundschreibenAll','rundschreibenAllTree','rundschreibenMeine','rundschreibenMeineTree') );
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
        $rundschreibenAll = Document::where('document_type_id' , $this->newsId )->orderBy('id', 'desc')->paginate(10, ['*'], 'alle-news');
        $rundschreibenAllTree = $this->document->generateTreeview( $rundschreibenAll );
        $rundschreibenMeine = Document::where('user_id',Auth::user()->id)
        ->where('document_type_id', $this->newsId )->orderBy('id', 'desc')
        ->take(10)->paginate(10, ['*'], 'meine-news');
        $rundschreibenMeineTree = $this->document->generateTreeview( $rundschreibenMeine );
        
        return view('dokumente.rundschreibenNews', compact('rundschreibenAll','rundschreibenAllTree','rundschreibenMeine','rundschreibenMeineTree') );
        
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
    private function fileUpload($model,$path,$files)
    {
      
        $folder = $this->pdfPath.str_slug($model->name);
        $uploadedNames = array();
        if ( ! \File::exists( $folder ) ) {
			\File::makeDirectory( $folder, $mod=0777,true,true); 
		}
		if( is_array($files) ){
		    $uploadedNames = array();
	        $counter=0;
		    foreach($files as $file){
		        if( is_array($file)){
		            	   
		            foreach($file as $f){
		                $counter++;
		                if($f !== NULL)
		                 $uploadedNames[] =  $this->moveUploaded($f,$folder,$model,$counter);
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
    private function moveUploaded($file,$folder,$model,$counter=0)
    {
        //$filename = $image->getClientOriginalName();
        $diffMarker = time()+$counter;
		$newName = str_slug($model->name).'-'.date("d-m-Y-H:i:s").'-'.$diffMarker.'.'.$file->getClientOriginalExtension();
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
        // $isoDocumentsAllFirst = Document::where('document_type_id',4)->paginate(10);
        $loggedInUser = \Auth::User();
       
        $documentsIso = Document::join('iso_categories','documents.iso_category_id','=','iso_categories.id')
        ->where('document_type_id', $this->isoDocumentId)
        ->where('slug',$slug)->get();//->paginate(10, ['*'], 'alle-iso-documents');
        
        $documentsIso = Document::join('iso_categories','documents.iso_category_id','=','iso_categories.id')
        ->join('editor_variants','documents.id','=','editor_variants.document_id')
        ->where('documents.document_type_id',$this->isoDocumentId)
        ->where('slug',$slug)
        //->get();
        ->paginate(10, ['*','iso_categories.name as isoCatName','documents.name as name'], 'alle-iso-documents');
        //  dd($documentsIso);
        
        $myIsoDocuments = Document::join('iso_categories','documents.iso_category_id','=','iso_categories.id')
        ->join('editor_variants','documents.id','=','editor_variants.document_id')
        ->where('documents.document_type_id',$this->isoDocumentId)
        ->where('slug',$slug)
        ->where('user_id',$loggedInUser->id)
        ->paginate(10, ['*','iso_categories.name as isoCatName','documents.name as name'], 'my-iso-documents');
            
        $documentsIsoTree = $this->document->generateTreeview($documentsIso);
        $myIsoDocumentsTree = $this->document->generateTreeview($myIsoDocuments);
        
        
        return view('dokumente.isoDocument', compact('documentsIso','documentsIsoTree','myIsoDocuments', 'myIsoDocumentsTree') );
        
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
        if( $model->isDirty() ||  $dirty == true )
            return true;
        return false;
       
    }
    
    /**
     * detect if model is dirty or not
     * @return bool 
     */
    private function generateUniqeLink($length=6){
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
// 	return $randStr; // Return the string
       
    }
    
   
}
