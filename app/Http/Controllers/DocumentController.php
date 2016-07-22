<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

use Request as RequestMerge;
use App\Helpers\ViewHelper;

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
use App\UserReadDocument;
use App\PublishedDocument;
use App\FavoriteDocument;
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
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->pluck('mandant_id');
     
        $documentTypes = DocumentType::all();
        $isoDocuments = IsoCategory::all();
        $documentStatus = DocumentStatus::all(); 
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
       
        $mandantUsers2 = User::leftJoin('mandant_users', 'users.id', '=', 'mandant_users.user_id')
        ->where('mandant_id', $mandantId)->get();
        $mandantUsers =  MandantUser::whereIn('mandant_id',$mandantId)->get()  ;  
        $mandantUsers = $this->clearUsers($mandantUsers);
        $incrementedQmr = Document::where('document_type_id',$this->qmRundId )->orderBy('qmr_number','desc')->first();
        if( count($incrementedQmr) < 1 )
            $incrementedQmr = 1;
        else{
            $incrementedQmr = $incrementedQmr->qmr_number;
            $incrementedQmr = $incrementedQmr+1;
        }
        
        $incrementedIso = Document::where('document_type_id',$this->isoDocumentId )->orderBy('iso_category_number','desc')->first();
        if( count($incrementedIso) < 1 )
            $incrementedIso = 1;
        else{
            $incrementedIso = $incrementedIso->iso_category_number;
            $incrementedIso = $incrementedIso+1;
        }
            //$this->qmRundId = 3;
        //$this->isoDocumentId = 4;
        $documentCoauthors = $mandantUsers;
        
        //this is until Neptun inserts the documents
        $documentUsers = $mandantUsers;
        
        return view('formWrapper', 
        compact('url', 'documentTypes', 'isoDocuments','documentStatus', 'mandantUsers', 'documentUsers','documentCoauthors','incrementedQmr','incrementedIso') );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $adressats = Adressat::where('active',1)->get();
        $docType = DocumentType::find( $request->get('document_type_id') );
        
        //fix if document type not iso category -> don't save iso_category_id
        if( $request->get('document_type_id') != $this->isoDocumentId )
            RequestMerge::merge(['iso_category_id' => null] );
            
        if( $request->get('document_type_id') != $this->newsId && $request->get('document_type_id') !=  $this->rundId
            && $request->get('document_type_id') != $this->qmRundId && $request->has('pdf_upload') )
            RequestMerge::merge(['pdf_upload' => 0] );    
    
        if(!$request->has('date_published'))    
            RequestMerge::merge(['date_published' => Carbon::now()->addDay()->format('d.m.Y')] );
        
        RequestMerge::merge(['version' => 1] );
        
        $setDocument = $this->document->setDocumentForm($request->get('document_type_id'), $request->get('pdf_upload')  );
        
        if( !$request->has('name_long') )
            RequestMerge::merge(['name_long' => $request->get('name')] );
        
        if( !$request->has('betreff') )   
            RequestMerge::merge(['betreff' => $request->get('name_long')] );
        
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
            // dd($model->documentUploads );
            
        $filename = '';
        $path = $this->pdfPath;
        //   dd($path);
        // dd($request->all() );
        if( $request->file() )
            $fileNames = $this->fileUpload($model,$path,$request->file() );
            
           //not summary, it is inhalt + attachment
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
            
            $folderName = $this->movePath."/".str_slug($model->name);
            
            foreach( $model->documentUploads as $oldUpload ){
                $filePath = $folderName."/".$oldUpload->file_path;
                // dd($filePath);
                \File::delete($filePath);
                $oldUpload->delete();
            }
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
            return redirect('dokumente/anlagen/'.$id );
            
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
            return redirect('dokumente/anlagen/'.$id );
            
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
        
        //   dd( $request->all() );
        $model = Document::find($request->get('model_id'));
        if($model == null){
            
            return redirect('dokumente/create')->with( array('message'=>'No Document with that id') );
        }
        //fix pdf checkbox
        if( !$request->has('show_name') )
            RequestMerge::merge(['show_name' => 0] );
            
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
        //check if has variant first count
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
        /* Preview link preparation */
        $setDocument = $this->document->setDocumentForm($data->document_type_id, $data->pdf_upload );
        $url = $setDocument->url;
        $form = $setDocument->form;
        $backButton = '/dokumente/'.$data->id.'/edit';
        $adressats = Adressat::where('active',1)->get();
        $currentVariant = 0;
        $previewUrl = '';
        if( $request->has('current_variant') ){
            $currentVariant = $request->get('current_variant');
        }
            
        if($request->has('preview') && $currentVariant != 0){
            $previewUrl = url('dokumente/ansicht/'.$id.'/'.$currentVariant);
            return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats','previewUrl') );
        }
        
        if($request->has('pdf_preview') && $currentVariant != 0){
            $previewUrl = url('dokumente/ansicht-pdf/'.$id.'/'.$currentVariant);
            return view('dokumente.formWrapper', compact('data','backButton','form','url','adressats','previewUrl') );
        }
        /* End Preview link preparation */
        
        
        if($request->has('attachment'))
            return redirect('dokumente/anlagen/'.$id );
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
    public function attachments($id,$preparedVariant=1)
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
        $documents = Document::where('document_type_id', $this->formulareId)
        ->where('document_status_id',1)->orWhere('document_status_id',3)->whereNotIn('id',array($id))->get();// documentTypeId 5 = Vorlagedokument
        foreach($documents as $document){
            $document->name = $document->name.' ('.$document->documentStatus->name.')';    
        }
        
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->first()->mandant_id;
        $attachmentArray = array();
        
        /*Check if document has editorVariant*/
        if( count( $data->editorVariantNoDeleted) > 0 ){
            foreach( $data->editorVariantNoDeleted as $variant){
                
                $attachmentArray[$variant->id] = $this->document->getAttachedDocumentLinks($variant, $id);
             }
        }
        /*End Check if document has attachments*/
        
        $url = '';
        $documentTypes = DocumentType::all();
        $isoDocuments = IsoCategory::all();
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
        
        $documentsFormulare = Document::where('document_type_id', $this->formulareId)->get();
       
        // dd($documentsFormulare);
       
        $mandantUsers = User::leftJoin('mandant_users', 'users.id', '=', 'mandant_users.user_id')
        ->where('mandant_id', $mandantId)->get();
         
        $collections = array();
        $roles = Role::all();
        //find variants
        $variants = EditorVariant::where('document_id',$data->id)->get();
        $documentStatus = DocumentStatus::all();
        
        return view('dokumente.attachments', compact('collections','data','data2','attachmentArray','documents', 'documentsFormulare', 'documentStatus', 'url', 'documentTypes',
        'isoDocuments','mandantUsers','backButton','nextButton','preparedVariant') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveAttachments(Request $request,$id,$preparedVariant=1)
    { 
        
        // dd( $request->all() );
        // option 1-> dodat dokument kao attachmet za trenutnu variantu i vorlage
        // option 2-> kreira se skroz novi dokument, ali se dodaje kao attchment postojećem
        //document attachment is a record in editor_variants && editor_variant_document
        $data = Document::find($id);
        
        if(!$request->has('date_published'))    
            RequestMerge::merge(['date_published' => Carbon::now()->addDay()] );
            
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
            // dd($request->all());
            $dt = DocumentType::find(  $this->formulareId = $this->formulareId);//vorlage document
            RequestMerge::merge(['version' => 1, 'document_type_id' => $dt->id,'is_attachment'=> 1] );
            
            if( !$request->has('name_long') )
                RequestMerge::merge(['name_long' => $request->get('name')] );
        
            if( !$request->has('betreff') )   
                RequestMerge::merge(['betreff' => $request->get('name_long')] );
                
            /*Create a new document*/
            $data = Document::create($request->all() );
            $lastId = Document::orderBy('id','DESC')->first();
            $lastId->document_group_id = $lastId->id;
            $lastId->save();
            
            
             if($request->has('document_coauthor') && $request->input('document_coauthor')[0] != "0" ){
                $coauthors = $request->input('document_coauthor');
                foreach($coauthors as $coauthor)
                    if( $coauthor != '0');
                        DocumentCoauthor::create(['document_id'=> $lastId->id, 'user_id'=> $coauthor]);
            }
            
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
            
            
        }
        
        if($request->has('next') )
            return redirect('dokumente/rechte-und-freigabe/'.$id );
    
    //   return redirect()->action('DocumentController@attachments', $id,$variant);
       return redirect('dokumente/anlagen/'.$id.'/'.$preparedVariant);
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
        foreach($variants as $variant){
            $variant->hasPreviousData = false;        
                foreach($mandants as $mandant){
                   
                    $selected = ViewHelper::setComplexMultipleSelect($variant,'documentMandantMandants', $mandant->id, 'mandant_id',true);
                    
                       
                }
        }
        
        return view('dokumente.anlegenRechteFreigabe', compact('collections',
        'mandants','mandantUsers','variants','roles','data','backButton') );
    }
    
    /**
     * Process the Rechte und freigabe request
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
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
        $processedArray = array();
        /* If variants exist in request */
        foreach($request->all() as $k => $v){
            /* If Variants are not empty */
            if (strpos($k, 'variante-') !== false && !empty($v) ){
                $hasVariants = true;
                $variantNumber = $this->document->variantNumber($k);
                $processedArray[] = $variantNumber;
                
                if( in_array('Alle',$request->get($k) ) && count( $request->get($k) ) <= 1 ){
                    ;$editorVariant = EditorVariant::where('document_id',$id)->where('variant_number',$variantNumber)->first();
                    if($editorVariant){
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
                     
                    }
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
        
            /*if removed mandants from variant return bool approval_all_mandants to true*/
                $editorVariants = EditorVariant::where('document_id',$id)->whereNotIn('variant_number',$processedArray)->get();
               
                foreach($editorVariants as $ev){
                    $ev->approval_all_mandants = 1; 
                    $dirty=$this->dirty($dirty,$ev);
                    $ev->save();
                }
            /*End if removed mandants from variant return bool approval_all_mandants to true*/
        
        /* End If variants exist in request */
        
        //fix when there are roles set, but no variants
        if( $hasVariants == false && $request->has('roles')){
          
            $editorVariantsNumbers = EditorVariant::where('document_id',$id)->get();
            
            foreach($editorVariantsNumbers as $editorVariant){
                $variantNumber = $editorVariant->variant_number;
                
                    $editorVariant->approval_all_mandants = 1; 
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
                
                    $editorVariant->approval_all_mandants = 1; 
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
            
          
            $publishedDocs =  PublishedDocument::where('document_id',$id)->first();
            if($publishedDocs == null){
                $continue = true;
                $uniqeUrl = '';
                while ($continue) {
                    $uniqeUrl = $this->generateUniqeLink();
                    if (PublishedDocument::where('url_unique',$uniqeUrl)->count() != 1)
                        $continue = false;
                }
                 $publishedDocs = PublishedDocument::create(['document_id'=> $id, 'document_group_id' => $document->document_group_id,
                            'url_unique'=>$uniqeUrl]);
                // $document->date_published = Carbon::now();
                // $document->save();
            }
            else
                $publishedDocs->fill(['document_id'=> $id, 'document_group_id' => $document->document_group_id])->save();
            
           
                            
            $otherDocuments = Document::where('document_group_id',$document->document_group_id)
                                ->whereNotIn('id',array($document->id))->get();
            foreach($otherDocuments as $oDoc){
                $oDoc->document_status_id = 5;
                $oDoc->save();
            }                                
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
        $datePublished = null;
        if( ctype_alnum($id) && !is_numeric($id) ){
            $publishedDocs = PublishedDocument::where('url_unique',$id)->first();
            $id = $publishedDocs->document_id;
            $datePublished = $publishedDocs->created_at;
            $document = Document::find($id);
            
            // add UserReadDocumen
            $readDocs = UserReadDocument::where('document_group_id', $publishedDocs->document_group_id)
                    ->where('user_id', Auth::user()->id)->get();
                    // dd($readDocs);
            if(count($readDocs) == 0){
                UserReadDocument::create([
                    'document_group_id'=> $publishedDocs->document_group_id, 
                    'user_id'=> Auth::user()->id, 
                    'date_read'=> Carbon::now(), 
                    'date_read_last'=> Carbon::now()
                ]);
            }
            
        }
        else{
            $document = Document::find($id);
        }
        $favorite =  FavoriteDocument::where('document_group_id',$document->document_group_id)->where('user_id', Auth::user()->id)->first();
        if( $favorite == null )
            $document->hasFavorite = false;
        else
            $document->hasFavorite = true;
        $documentComments = DocumentComment::where('document_id',$id)->where('freigeber',0)->orderBy('id','DESC')->get();
        $variants = EditorVariant::where('document_id',$id)->get();
        
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id',Auth::user()->id)->pluck('mandant_id');
        $mandantIdArr = $mandantId->toArray();
        $mandantRoles =  MandantUserRole::whereIn('mandant_user_id',$mandantId)->pluck('role_id');
        $mandantRolesArr =  $mandantRoles->toArray();
        $auth = DocumentApproval::where('document_id',$id)->get(); 
        
        /* Button check */
        $published = false;
        $canPublish = false;
        $authorised = false;
        $authorisedPositive = false;
             //  je li date expired manji od now
        if($document->date_expired != null){
            $dateExpired = new Carbon($document->date_expired);
            $now = new Carbon( Carbon::now() );    
            $datePassed = $dateExpired->lt( Carbon::now() );
            if( $datePassed == true)
                $canPublish = true;
        }
             
        if( count($document->documentApprovalsApprovedDateNotNull) > 0){
            $authorised = true;
           
            foreach($document->documentApprovals->pluck('approved') as $approved){
                if($approved == true)
                    $authorisedPositive = true;
            }
        }
       
        if( count( $document->publishedDocuments->first() ) > 0)
            $published = true;
        // dd($authorised);
        // dd($canPublish);
        /* End Button check */
        
        $hasPermission = false;
        foreach($variants as $variant){
            if($hasPermission == false){
                if($variant->approval_all_mandants == true){
                    if($document->approval_all_roles == true){
                            $hasPermission = true;
                            $variant->hasPermission = true;
                        }
                        else{
                            foreach($variant->documentMandantRoles as $role){
                                if( in_array($role->role_id, $mandantRolesArr) ){
                                 $variant->hasPermission = true;
                                 $hasPermission = true;
                                }
                            }//end foreach documentMandantRoles
                        }
                }
                else{
                    foreach( $variant->documentMandantMandants as $mandant){
                        if( in_array($mandant->mandant_id,$mandantIdArr) ){
                            if($document->approval_all_roles == true){
                                $hasPermission = true;
                                $variant->hasPermission = true;
                            }
                            else{
                                foreach($variant->documentMandantRoles as $role){
                                    if( in_array($role->role_id, $mandantRolesArr) ){
                                     $variant->hasPermission = true;
                                     $hasPermission = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                        }
                    }//end foreach documentMandantMandants
                }
            }
        }
        return view('dokumente.show', compact('document', 'documentComments', 'variants', 'published', 'datePublished', 'canPublish', 'authorised') );
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
        // dd($data);
        if($data == null)
            return redirect('dokumente/create');

        $url = 'PATCH';
        
        $url = '';
        $documentCoauthor = DocumentCoauthor::where('document_id', $id)->get();
        $documentTypes = DocumentType::all();
        $isoDocuments = IsoCategory::all();
        $mandantUserRoles = MandantUserRole::where('role_id',10)->pluck('mandant_user_id');
        $documentStatus = DocumentStatus::all();
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->pluck('mandant_id');
        $mandantUsers =  MandantUser::distinct('user_id')->whereIn('mandant_id',$mandantId)->get();  
        $mandantUsers = $this->clearUsers($mandantUsers);
        $documentCoauthor = $mandantUsers;
        
        //this is until Neptun inserts the documents
        $documentUsers = $mandantUsers;
        
        $incrementedQmr = Document::where('document_type_id',$this->qmRundId )->orderBy('qmr_number','desc')->first();
        if( $incrementedQmr == null || $incrementedQmr->qmr_number == null )
            $incrementedQmr = 1;
        else{
            $incrementedQmr = $incrementedQmr->qmr_number;
            $incrementedQmr = $incrementedQmr+1;
        }
        
        $incrementedIso = Document::where('document_type_id',$this->isoDocumentId )->orderBy('iso_category_number','desc')->first();
        if( count($incrementedIso) < 1   || $data == null)
            $incrementedIso = 1;
        else{
            $incrementedIso = $incrementedIso->iso_category_number;
            $incrementedIso = $incrementedIso+1;
        }
       
        return view('formWrapper', compact('data','method','url','documentTypes','isoDocuments',
        'documentStatus','mandantUsers','$documentUsers', 'documentCoauthor','incrementedQmr','incrementedIso') );
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
        
        //fix pdf checkbox
        if( !$request->has('pdf_upload') )
            RequestMerge::merge(['pdf_upload' => 0] );
            
        //if doc type formulare set ladnsace to null
        if( !$request->has('landscape') )
            RequestMerge::merge(['landscape' => 0] );
        
            
        if( $request->get('document_type_id') != $this->newsId && $request->get('document_type_id') !=  $this->rundId
            && $request->get('document_type_id') !=  $this->qmRundId  && $request->has('pdf_upload') )
            RequestMerge::merge(['pdf_upload' => 0] );  
            
        if( !$request->has('name_long') )
            RequestMerge::merge(['name_long' => $request->get('name')] );
        
        if( !$request->has('betreff') )   
            RequestMerge::merge(['betreff' => $request->get('name_long')] );
    
         if(!$request->has('date_published') || $request->get('date_expired') == 'date_published')    
             RequestMerge::merge(['date_published' => null] );
         
         if(!$request->has('date_expired') || $request->get('date_expired') == '' )    
             RequestMerge::merge(['date_expired' => null] );
             
        // dd($request->all() );
        $data = Document::find( $id );
        $prevName = $data->name;
        
        if( $data->document_type_id == $this->formulareId )
            RequestMerge::merge(['landscape' => 0] );
        
        // dd( $request->all() );        
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
    public function newVersion($id)
    {
        //find if document has version higher than one
        $document = Document::find($id);
        $highestVersion = Document::where('document_group_id',$document->document_group_id)->orderBy('version','DESC')->first();
        $version = $highestVersion->version;
      
        /*Set all previous versions to arhived*/
        /*End Set all previous versions to arhived*/
        $newDocument = $document->replicate();
        $newDocument->version = $version+1;
        $newDocument->version_parent = $version;
        $newDocument->document_status_id = 1;
        $newDocument->date_expired = null;
        $newDocument->date_published = null;
        $newDocument->date_approved = null;
        $newDocument->save();
        
        /*Duplicate document variants*/
        foreach($document->editorVariant as $variant){
            $newVariant = $variant->replicate();
            $newVariant->document_id = $newDocument->id;
            $newVariant->save();
            
            /*Duplicate document uploads*/
            foreach( $variant->documentUpload as $upload){
                // $oldSlug= str_slug($document->name);
                // $newSlug= str_slug($newDocument->name);
        // 		$newFileName = str_replace($oldSlug,$newSlug, $upload->file_path);
                $newUpload = $upload->replicate();
                $newUpload->editor_variant_id = $newVariant->id;
                $newUpload->file_path = $upload->file_path;
                $newUpload->save();
                
                 if ( ! \File::exists( $this->movePath.'/'.$newDocument->id.'/' ) ) {
        			\File::makeDirectory( $this->movePath.'/'.$newDocument->id.'/', $mod=0777,true,true); 
        		}
               copy($this->movePath.'/'.$document->id.'/'.$upload->file_path, $this->movePath.'/'.$newDocument->id.'/'.$upload->file_path);
            //   $upload->file_path = $newFileName;
                
            }
            /*End Duplicate document uploads*/
            
            /*Duplicate editor_variant_documents*/
            foreach( $variant->editorVariantDocument as $editorVariantDocument){
                $newEditorVariantDocument = $editorVariantDocument->replicate();
                $newEditorVariantDocument->editor_variant_id = $newVariant->id;
                $newEditorVariantDocument->document_status_id = $newDocument->document_status_id;
                $newEditorVariantDocument->document_group_id = $newDocument->document_group_id;
                $newEditorVariantDocument->document_id = $newDocument->id;
                $newEditorVariantDocument->save();
            }
            /*End Duplicate editor_variant_documents*/
            
            /*Duplicate document mandants*/
            foreach( $variant->documentMandants as $documentMandant){
                $newDocumentMandant = $documentMandant->replicate();
                $newDocumentMandant->document_id = $newDocument->id;
                $newDocumentMandant->editor_variant_id = $newVariant->id;
                $newDocumentMandant->save();
                
                /*Duplicate document mandant mandants*/
                foreach($documentMandant->documentMandantMandants as $docMandantMandant){
                    $newDMM = $docMandantMandant->replicate();
                    $newDMM->document_mandant_id = $newDocumentMandant->id;
                    $newDMM->save();
                }
                /*End Duplicate document mandant mandants*/
                
                /*Duplicate document mandant roles*/
                 foreach($documentMandant->documentMandantRole as $docMandantRole){
                    $newDMR = $docMandantRole->replicate();
                    $newDMR->document_mandant_id = $newDocumentMandant->id;
                    $newDMR->save();
                }
                /*End Duplicate document mandant roles*/
            }   
            /*End Duplicate  document mandants*/
        }
        /* End Duplicate document variants*/
        // dd($newDocument);
         session()->flash('message',trans('documentForm.newVersionSuccess'));
        return redirect('dokumente/'.$newDocument->id.'/edit' );
        
    }
    
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyByLink($documentId,$editorId,$editorDocumentId)
    {
        $documentCheck = EditorVariantDocument::where('editor_variant_id',$editorId)->where('document_id',$editorDocumentId)->first();
        
        if($documentCheck != null)
            $documentCheck->delete();
       
        return redirect('/dokumente/anlagen/'.$documentId)->with('message', 'Dokument wurde entfernt.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect('mandanten')->with('message', 'Benutzer erfolgreich entfernt.');
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
        RequestMerge::merge(['document_id'=>$id,'user_id' => Auth::user()->id,'active' => 1,'freigeber'=>0] );
        //  dd( $request->all() );
        $comment =  DocumentComment::create( $request->all() );
        session()->flash('message',trans('documentForm.savedComment'));
        if( $request->has('page') )
            return redirect('dokumente/'.$id);
        else
             return redirect('dokumente/'.$id.'/freigabe');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function freigabeApproval($id)
    {
        $document = Document::find($id);
        $variants = EditorVariant::where('document_id',$id)->get();
        $documentCommentsUser = DocumentComment::where('document_id',$id)->where('freigeber',0)->orderBy('id','DESC')->get();
        $documentCommentsFreigabe = DocumentComment::where('document_id',$id)->where('freigeber',1)->orderBy('id','DESC')->get();
        /* Button check */
        $published = false;
        $canPublish = false;
        $authorised = false;
        $authorisedPositive = false;
        if($document->date_expired != null){
            $dateExpired = new Carbon($document->date_expired);
            $now = new Carbon( Carbon::now() );     
            $datePassed = $dateExpired->lt( Carbon::now() );
            if( $datePassed == true)
                $canPublish = true;
        }
        if( count($document->documentApprovalsApprovedDateNotNull ) > 0){
            $authorised = true;
            
        }
        
        if( count( $document->publishedDocuments->first() ) > 0)
            $published = true;
        
        $hasPermission = false;
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id',Auth::user()->id)->pluck('mandant_id');
        $mandantIdArr = $mandantId->toArray();
        foreach($variants as $variant){
            if($hasPermission == false){
                if($variant->approval_all_mandants == true){
                    if($document->approval_all_roles == true){
                            $hasPermission = true;
                            $variant->hasPermission = true;
                        }
                        else{
                            foreach($variant->documentMandantRoles as $role){
                                if( in_array($role->role_id, $mandantRolesArr) ){
                                 $variant->hasPermission = true;
                                 $hasPermission = true;
                                }
                            }//end foreach documentMandantRoles
                        }
                }
                else{
                    foreach( $variant->documentMandantMandants as $mandant){
                        if( in_array($mandant->mandant_id,$mandantIdArr) ){
                            if($document->approval_all_roles == true){
                                $hasPermission = true;
                                $variant->hasPermission = true;
                            }
                            else{
                                foreach($variant->documentMandantRoles as $role){
                                    if( in_array($role->role_id, $mandantRolesArr) ){
                                     $variant->hasPermission = true;
                                     $hasPermission = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                        }
                    }//end foreach documentMandantMandants
                }
            }
        }
        
        /* End Button check */
        return view('dokumente.freigabe',compact('document','variants','documentCommentsUser','documentCommentsFreigabe','published',
        'canPublish','hasPermission','authorised','authorisedPositive'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function publishApproval($id)
    {
        $document = Document::find($id);
        $document->document_status_id = 3;
        // $document->date_published = Carbon::now();
        $document->save();
        $continue = true;
        $uniqeUrl = '';
        while ($continue) {
            $uniqeUrl = $this->generateUniqeLink();
            if (PublishedDocument::where('url_unique',$uniqeUrl)->count() != 1)
                $continue = false;
        }
        $publishedDocs =  PublishedDocument::where('document_id',$id)->first();
            if($publishedDocs == null){
                $continue = true;
                $uniqeUrl = '';
                while ($continue) {
                    $uniqeUrl = $this->generateUniqeLink();
                    if (PublishedDocument::where('url_unique',$uniqeUrl)->count() != 1)
                        $continue = false;
                }
                $publishedDocs = PublishedDocument::create(['document_id'=> $id, 'document_group_id' => $document->document_group_id,
                            'url_unique'=>$uniqeUrl]);
                $publishedDocs->fill(['document_id'=> $id, 'document_group_id' => $document->document_group_id])->save();
            }
            else
                $publishedDocs->fill(['document_id'=> $id, 'document_group_id' => $document->document_group_id])->save();
                
        if($document->published->url_unique)    
            return redirect('dokumente/'.$document->published->url_unique);
        else
            return redirect('dokumente/'.$id);
    }
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function favorites($id)
    {
        $document = Document::find($id);
        $favoriteCheck = FavoriteDocument::where('document_group_id',$document->document_group_id)->where('user_id', Auth::user()->id)->first();
        // dd($document->published);
        if( $favoriteCheck == null )
            $favorite = FavoriteDocument::create( ['document_group_id'=> $document->document_group_id, 'user_id' => Auth::user()->id ]);
        else
            $favoriteCheck->delete();
            
        // if($document->published->url_unique)    
        //     return redirect('dokumente/'.$document->published->url_unique);
        // else
        //     return redirect('dokumente/'.$id);
        
        return back();
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentActivation($id)
    {
        $document = Document::find($id);
        if($document->active == true)
            $document->active = false;
        else
            $document->active = true;
            
        $document->save();
        
        if($document->published->url_unique)    
            return redirect('dokumente/'. $document->published->url_unique);
        else
            return redirect('dokumente/'.$id);
    }
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePdf($id)
    {
        $dateNow = Carbon::now()->format('M Y');
        if( ctype_alnum($id) && !is_numeric($id) ){
            $publishedDocs = PublishedDocument::where('url_unique',$id)->first();
            $id = $publishedDocs->document_id;
            $document = Document::find($id);
        }
        else{
            $document = Document::find($id);
        }
        $favorite =  FavoriteDocument::where('document_group_id',$document->document_group_id)->where('user_id', Auth::user()->id)->first();
        if( $favorite == null )
            $document->hasFavorite = false;
        else
            $document->hasFavorite = true;
        $documentComments = DocumentComment::where('document_id',$id)->where('freigeber',0)->get();
        $variants = EditorVariant::where('document_id',$id)->get();
        
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id',Auth::user()->id)->pluck('mandant_id');
        $mandantIdArr = $mandantId->toArray();
        $mandantRoles =  MandantUserRole::whereIn('mandant_user_id',$mandantId)->pluck('role_id');
        $mandantRolesArr =  $mandantRoles->toArray();
        
        $hasPermission = false;
        // dd($mandantId);
        
            
        foreach($variants as $variant){
            if($hasPermission == false){
                if($variant->approval_all_mandants == true){
                    if($document->approval_all_roles == true){
                            $hasPermission = true;
                            $variant->hasPermission = true;
                        }
                        else{
                            foreach($variant->documentMandantRoles as $role){
                                if( in_array($role->role_id, $mandantRolesArr) ){
                                 $variant->hasPermission = true;
                                 $hasPermission = true;
                                }
                            }//end foreach documentMandantRoles
                        }
                }
                else{
                    foreach( $variant->documentMandantMandants as $mandant){
                        if( in_array($mandant->mandant_id,$mandantIdArr) ){
                            if($document->approval_all_roles == true){
                                $hasPermission = true;
                                $variant->hasPermission = true;
                            }
                            else{
                                foreach($variant->documentMandantRoles as $role){
                                    if( in_array($role->role_id, $mandantRolesArr) ){
                                     $variant->hasPermission = true;
                                     $hasPermission = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                        }
                    }//end foreach documentMandantMandants
                }
            }
        }
        
        $document = Document::find($id);
         
         $pdf = \PDF::loadView('pdf.document', compact('document','variants','dateNow'));
         
        /* If document type Iso Category load different PDF template*/    
         if($document->document_type_id == $this->isoDocumentId){
             
             $html = view('pdf.documentIso', compact('document','variants','dateNow'))->render();
            //  return $html;
             $pdf = \PDF::loadHTML($html);
         }
            
        /* End If document type Iso Category load different PDF template*/    
        
        /* If landscape is true set paper to landscape */    
        if($document->landscape == true)
            $pdf->setPaper('A4', 'landscape');
        /* End If landscape is true set paper to landscape */
        // $pdf->set_option('isHtml5ParserEnabled', true);
        return $pdf->stream();
    }
    
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePdfPreview($id,$editorId)
    {
        $dateNow = Carbon::now()->format('M Y');
        if( ctype_alnum($id) && !is_numeric($id) ){
            $publishedDocs = PublishedDocument::where('url_unique',$id)->first();
            $id = $publishedDocs->document_id;
            $document = Document::find($id);
        }
        else
            $document = Document::find($id);
        $variants = EditorVariant::where('document_id',$id)->where('variant_number',$editorId)->get();
        foreach($variants as $variant)
            $variant->hasPermission = true;
        
        $pdf = \PDF::loadView('pdf.document', compact('document','variants','dateNow'));
       
        /* If document type Iso Category load different PDF template*/    
        /* if($document->document_type_id == $this->isoDocumentId)
            $pdf = \PDF::loadView('pdf.documentIso', compact('document','variants','dateNow'));*/
        /* End If document type Iso Category load different PDF template*/    
        
        /* If landscape is true set paper to landscape */    
        if($document->landscape == true)
            $pdf->setPaper('A4', 'landscape');
        /* End If landscape is true set paper to landscape */
            
        return $pdf->stream();
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function previewDocument($id,$editorId)
    {
        if( ctype_alnum($id) && !is_numeric($id) ){
            $publishedDocs = PublishedDocument::where('url_unique',$id)->first();
            $id = $publishedDocs->document_id;
            $document = Document::find($id);
        }
        else
            $document = Document::find($id);
        
        $favorite =  FavoriteDocument::where('document_group_id',$document->document_group_id)->where('user_id', Auth::user()->id)->first();
        if( $favorite == null )
            $document->hasFavorite = false;
        else
            $document->hasFavorite = true;
        $documentComments = DocumentComment::where('document_id',$id)->where('freigeber',0)->get();
        $variants = EditorVariant::where('document_id',$id)->where('variant_number',$editorId)->get();
        
            
        foreach($variants as $variant)
            $variant->hasPermission = true;
        
        return view('dokumente.showPreview', compact('document','documentComments','variants') );
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function previewPdf($id)
    {
        if( ctype_alnum($id) && !is_numeric($id) ){
            $publishedDocs = PublishedDocument::where('url_unique',$id)->first();
            $id = $publishedDocs->document_id;
            $document = Document::find($id);
        }
        else{
            $document = Document::find($id);
        }
        $favorite =  FavoriteDocument::where('document_group_id',$document->document_group_id)->where('user_id', Auth::user()->id)->first();
        if( $favorite == null )
            $document->hasFavorite = false;
        else
            $document->hasFavorite = true;
        $documentComments = DocumentComment::where('document_id',$id)->where('freigeber',0)->get();
        $variants = EditorVariant::where('document_id',$id)->get();
        
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id',Auth::user()->id)->pluck('mandant_id');
        $mandantIdArr = $mandantId->toArray();
        $mandantRoles =  MandantUserRole::whereIn('mandant_user_id',$mandantId)->pluck('role_id');
        $mandantRolesArr =  $mandantRoles->toArray();
        
        $hasPermission = false;
        // dd($mandantId);
        
            
        foreach($variants as $variant){
            if($hasPermission == false){
                if($variant->approval_all_mandants == true){
                    if($document->approval_all_roles == true){
                            $hasPermission = true;
                            $variant->hasPermission = true;
                        }
                        else{
                            foreach($variant->documentMandantRoles as $role){
                                if( in_array($role->role_id, $mandantRolesArr) ){
                                 $variant->hasPermission = true;
                                 $hasPermission = true;
                                }
                            }//end foreach documentMandantRoles
                        }
                }
                else{
                    foreach( $variant->documentMandantMandants as $mandant){
                        if( in_array($mandant->mandant_id,$mandantIdArr) ){
                            if($document->approval_all_roles == true){
                                $hasPermission = true;
                                $variant->hasPermission = true;
                            }
                            else{
                                foreach($variant->documentMandantRoles as $role){
                                    if( in_array($role->role_id, $mandantRolesArr) ){
                                     $variant->hasPermission = true;
                                     $hasPermission = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                        }
                    }//end foreach documentMandantMandants
                }
            }
        }
        return view('dokumente.show', compact('document','documentComments','variants') );
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function authorizeDocument(Request $request, $id)
    {
        // dd($request->all());
        if($request->get('validation_status') == 1){
            $approved = true;
            $dateApproved = Carbon::now();
        }
        else{
            $approved = false;
            $dateApproved = Carbon::now();
        } 
         
        $document = Document::find($id);
        $user = Auth::user()->id;
        $document->document_status_id = 2; 
        $document->save();
        $continue = true;
        $uniqeUrl = '';
        while ($continue) {
            $uniqeUrl = $this->generateUniqeLink();
            if (PublishedDocument::where('url_unique',$uniqeUrl)->count() != 1)
                $continue = false;
        }
        $documentApproval = DocumentApproval::firstOrCreate( array('document_id' => $id, 'user_id' => $user) );
        $documentApproval->approved = $approved;
        $documentApproval->date_approved = $dateApproved;
        $documentApproval->save();
        
        $otherDocuments = Document::where('document_group_id',$document->document_group_id)->whereNotIn('id',array($document->id))->get();
        foreach($otherDocuments as $oDoc){
            $oDoc->document_status_id = 5;
            $oDoc->save();
        }                 
        
        if($request->has('comment')){
            RequestMerge::merge(['freigeber' => 1,'active' => 1,'document_id'=>$document->id,'user_id' => $user] );
            $comment = DocumentComment::create( $request->all() );
        }
        
        
        $publishedDocs =  PublishedDocument::where('document_id',$id)->first();
            if($publishedDocs == null){
                $continue = true;
                $uniqeUrl = '';
                while ($continue) {
                    $uniqeUrl = $this->generateUniqeLink();
                    if (PublishedDocument::where('url_unique',$uniqeUrl)->count() != 1)
                        $continue = false;
                }
                 $publishedDocs = PublishedDocument::create(['document_id'=> $id, 'document_group_id' => $document->document_group_id,
                            'url_unique'=>$uniqeUrl]);
            
                // $document->date_published = Carbon::now();
                $document->save();
            }
            else
                $publishedDocs->fill(['document_id'=> $id, 'document_group_id' => $document->document_group_id])->save();
        session()->flash('message',trans('documentForm.authorized'));
        return redirect('/dokumente/'.$id.'/freigabe');
    }
    
     /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rundschreiben()
    {
        $docType = $this->rundId;
        $rundschreibenAll = Document::where(['document_type_id' =>  $docType])->where('document_status_id',3)->orderBy('id', 'desc')->paginate(10, ['*'], 'alle-rundschreiben');
        $rundschreibenAllTree = $this->document->generateTreeview( $rundschreibenAll );
        $rundschreibenMeine = Document::where(['user_id' => Auth::user()->id, 'document_type_id' =>  $this->rundId])->orderBy('id', 'desc')->take(10)->paginate(10, ['*'], 'meine-rundschreiben');
        $rundschreibenMeineTree = $this->document->generateTreeview( $rundschreibenMeine );
        
        return view('dokumente.rundschreiben', compact('docType', 'rundschreibenAll', 'rundschreibenAllTree', 'rundschreibenMeine', 'rundschreibenMeineTree') );
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
        $docType = $this->qmRundId;
        $qmrMyPaginated = Document::where('document_type_id' , $this->qmRundId )->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10, ['*'], 'meine-qmr');
        $qmrMyTree = $this->document->generateTreeview( $qmrMyPaginated );
        
        $qmrAllPaginated = Document::where('document_type_id' , $this->qmRundId )->where('document_status_id',3)
        ->orderBy('id', 'desc')->paginate(10, ['*'], 'alle-qmr');
        $qmrAllTree = $this->document->generateTreeview( $qmrAllPaginated );
        
        return view('dokumente.circularQMR', compact('docType', 'qmrMyTree', 'qmrMyPaginated', 'qmrAllTree', 'qmrAllPaginated'));
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rundschreibenNews()
    {
        $rundschreibenAll = Document::where('document_type_id' , $this->newsId )->where('document_status_id',3)->orderBy('id', 'desc')->paginate(10, ['*'], 'alle-news');
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
        $formulareAll = Document::where(['document_type_id' =>  $this->formulareId])->where('document_status_id',3)->orderBy('id', 'desc')->paginate(10, ['*'], 'alle-formulare');
        $formulareAllTree = $this->document->generateTreeview( $formulareAll );
        $formulareMeine = Document::where(['user_id' => Auth::user()->id, 'document_type_id' =>  $this->formulareId])
        ->orderBy('id', 'desc')->take(10)->paginate(10, ['*'], 'meine-formulare');
        $formulareMeineTree = $this->document->generateTreeview( $formulareMeine );
        
        return view('dokumente.documentTemplates', compact('formulareAll','formulareAllTree','formulareMeine','formulareMeineTree') );
    }
    
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentType($type)
    {
        $documentType = null;
        $documentsByTypePaginated = array();
        $documentsByTypeTree = array();
        
        foreach(DocumentType::all() as $docType){
            if(str_slug($docType->name) == $type){
                $documentType = $docType;
                break;
            }
        }
        
        if(isset($documentType)){
            $documentsByTypePaginated = Document::where('document_type_id', $documentType->id)->where('deleted_at', null)->orderBy('id', 'desc')->paginate(10, ['*'], 'seite');
            $documentsByTypeTree = $this->document->generateTreeview($documentsByTypePaginated);
        }
        
        return view('dokumente.documentType', compact('documentType', 'documentsByTypeTree', 'documentsByTypePaginated' ) );
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
        $document = Document::find($id);
        $documentHistory = Document::where('document_group_id', $document->document_group_id)->orderBy('id', 'desc')->paginate(10, ['*'], 'dokument-historie');
        $documentHistoryTree = $this->document->generateTreeview( $documentHistory, array('pageHistory' => true) );
        // dd($documentHistory);
        return view('dokumente.historie', compact('document', 'documentHistory', 'documentHistoryTree') );
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
      
        $folder = $this->pdfPath.str_slug($model->id);
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
		$newName = str_slug($model->id).'-'.date("d-m-Y-H:i:s").'-'.$diffMarker.'.'.$file->getClientOriginalExtension();
		$path = "$folder/$newName";
// 		dd($path);
        $filename = $file->getClientOriginalName();
        $uploadSuccess = $file->move($folder, $newName );
      	\File::delete($folder .'/'. $filename);
      	return $newName;
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
     * Display the documents for the specified ISO category slug.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function isoCategoriesIndex()
    {
        $isoCategories = IsoCategory::all();
        return view('dokumente.isoCategoriesIndex', compact('isoCategories'));
    }
    
     /**
     * Search documents by request parameters.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        if(empty($request->all())) return redirect('/');
        
        $docType = $request->input('document_type_id');
        $docTypeName = DocumentType::find($docType)->name;
        $search = $request->input('search');
        
        $resultAllPaginated = Document::where(['document_type_id' =>  $docType])
        ->where('name', 'LIKE' ,'%'.$search.'%')->orderBy('id', 'desc')
        ->paginate(10, ['*'], 'results-all');
        $resultAllTree = $this->document->generateTreeview($resultAllPaginated);
        
        $resultMyPaginated = Document::where(['user_id' => Auth::user()->id, 'document_type_id' => $docType])
        ->where('name', 'LIKE' ,'%'.$search.'%')->orderBy('id', 'desc')
        ->paginate(10, ['*'], 'results-my');
        $resultMyTree = $this->document->generateTreeview($resultMyPaginated);
        
        // return back()->withInput()->with(compact('docType', 'resultAllPaginated', 'resultAllTree', 'resultMyPaginated', 'resultMyTree'));
        return view('dokumente.suchergebnisse')->with(compact('search', 'docType', 'docTypeName', 'resultAllPaginated', 'resultAllTree', 'resultMyPaginated', 'resultMyTree'));
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
    private function clearUsers($users){
        $clearedArray = array();
            foreach($users as $k => $user){
                if( !in_array($user->user_id, $clearedArray ) )
                    $clearedArray[] = $user->user_id;
                else
                    unset($users[$k]);
            }
        return $users;
       
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
       
    }
    
    
}
