<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Illuminate\Support\Facades\File;

use App\Helpers\OcrHelper;
use App\Document;
use App\EditorVariant;
use App\DocumentUpload;
use App\User;

/**
 * Imports all uploaded files into the Juristenportal
 * 
 * @author Mirko Rosenthal <mirko.rosenthal@webbite.de>
 */
class JuristenPortalImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'juristenportal:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reads all files in the ocrUploads Folder and moves them to the correct places';


    /**
     * Path to the upload folder
     * 
     * @var string
     */
    protected $portalOcrUploads;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->portalOcrUploads = public_path().'/files/juristenportal/ocr-uploads/';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $files = File::files($this->portalOcrUploads);
        foreach($files as $file){
            $this->importFile($file);
        }
    }
    

    /**
     * Handles the import of a file
     * @param string $fileName Name of the file
     */
    public function importFile($fileName){
        $ocrHelper = new OcrHelper($this->portalOcrUploads, $fileName);
        
      //  dd($ocrHelper->getFileBaseName());

       // $explode = explode('/', $fileName);
       // $filteredNameFirst = last($explode);
       // $originalDocument = $filteredNameFirst;
        $documentExistsFirstTest =  Document::where('name','LIKE','%'.$ocrHelper->getFileBaseName().'%')
                                            ->orWhere('name', 'LIKE','%'.$ocrHelper->getFileName().'%')
                                            ->first();
        //Check if we somehow already have this file in the db
        if(count($documentExistsFirstTest) != 0){
            return false;
        }

        $text = $ocrHelper->extractText();
        $metaData = $ocrHelper->getMetaData();

        if(strpos($text, 'DUMMY DOCUMENT') !== false){
            //Ignore the dummy file
            return false;
        }
        
        $ocrHelper->setFilename($fileName); /* Restore original filename */
        
        $converted_file = $ocrHelper->convertToPDF();
        //$converted_file = $ocrHelper->convertToPdfObject();
        
        $explode = explode('/', $fileName);
        $filteredName = last($explode);
        
        $user_id = 1;
        $user = null;
        $userParts = [];

        if(isset($metaData['Last Modified By'])){
            $userParts = explode(' ', $metaData['Last Modified By'], 2);
        }else if(isset($metaData['Creator'])){
            $userParts = explode(' ', $metaData['Creator'], 2);
        }
        
        //Check Mirko Rosenthal and Rosenthal Mirko in Database
        //because we don't know the order of first and last name in the meta data
        if(count($userParts == 2)){
             $user = User::where('first_name', $userParts[0])
                    ->where('last_name', $userParts[1])
                    ->orWhere(function ($query) use($userParts){
                        $query->where('first_name', $userParts[1])
                              ->where('last_name', $userParts[0]);
                    })
                    ->first();
        }
       
        if($user != null){
            $user_id = $user->id;
        }
        
        $document = new Document();
        $document->document_type_id = null;
        $document->user_id = $user_id;
        if(isset($metaData['Title'])){
            $document->name = $metaData['Title'];
            $document->name_long = $metaData['Title'];
        }else{
            $document->name = $ocrHelper->getFileBaseName();
            $document->name_long = $ocrHelper->getFileBaseName();
        }
        if(isset($metaData['Keywords'])){
            $document->search_tags = $metaData['Keywords'];
        }
        if(isset($metaData['Description'])){
            $document->summary = $metaData['Description'];
        }
        $document->owner_user_id = $user_id;
        $document->version = 1;
        $document->save();
        
        $editor_variant = new EditorVariant();
        $editor_variant->document_id = $document->id;
        $editor_variant->variant_number = 1;
        $editor_variant->inhalt = $text;
        $editor_variant->save();

        
        $document_dir = public_path() . '/files/documents/'. $document->id.'/' ;
        File::makeDirectory($document_dir, 0777, true);
        File::move($fileName, $document_dir . $ocrHelper->getFileBaseName());
        $document_upload_original = new DocumentUpload();
        $document_upload_original->editor_variant_id = $editor_variant->id;
        $document_upload_original->file_path = $ocrHelper->getFileBaseName();
        $document_upload_original->save();
        
        if (!File::exists($document_dir)) {
            File::makeDirectory($document_dir, $mod = 0777, true, true);
        }
        if (File::exists( $this->portalOcrUploads.'/'. $converted_file)) {
            File::move( $this->portalOcrUploads.'/'.$converted_file ,$document_dir . $converted_file );
            $document_upload_converted = new DocumentUpload();
            $document_upload_converted->editor_variant_id = $editor_variant->id;
            $document_upload_converted->file_path = $converted_file ;
            $document_upload_converted->save();   
        }
        
        /*
       
        if( count($documentExistsFirstTest) < 1 ){
          

            
            $documentExists = Document::where('name',$fileName)->first();
            if( is_null($documentExists) ){
               
                
                $justName = explode('.',$fileName);
                $justName = $justName[0];
                
                $files = File::allFiles($this->portalOcrUploads);
                // dd($files);
                foreach ($files as $file){
                    if( strpos($file->getPathname(), 'cache') !== false
                    ||  strpos($file->getPathname(), 'libreoffice') !== false
                    ||  strpos($file->getPathname(), 'output') !== false ) { 
                        continue;
                    }            
                    
                    $name = explode('/',(string)$file);
                    $name = last($name);
                 
                    $justFileName = explode('.',$name);
                    $justFileName = $justFileName[0];
                    
                    if( strpos($file->getPathname(), $justFileName) !== false && 
                    strpos($file->getPathname(), 'output') === false  ){
                  
                        if (!File::exists($document_dir)) {
                            File::makeDirectory($document_dir, $mod = 0777, true, true);
                        }
                        if (File::exists( $this->portalOcrUploads.'/'.$file->getFilename())) {
                            File::move( $this->portalOcrUploads.'/'.$file->getFilename() ,$document_dir . $file->getFilename() );
                            $document_upload_converted = new DocumentUpload();
                            $document_upload_converted->editor_variant_id = $editor_variant->id;
                            $document_upload_converted->file_path = $file->getFilename() ;
                            $document_upload_converted->save();   
                        }
                           
                    }
                }
                  
            }

        }
                    */
    }
}
