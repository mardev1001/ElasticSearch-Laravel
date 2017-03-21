<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Illuminate\Support\Facades\File;

use App\Helpers\OcrHelper;
use App\Document;
use App\EditorVariant;
use App\DocumentUpload;

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
        $this->portalOcrUploads = public_path().'/files/juristenportal/ocr-uploads';
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
        $explode = explode('/', $fileName);
        $filteredName = last($explode);
        $explode = explode('.',$filteredName);
        $nameFil = $explode[0];
        $documentExistsFirstTest =  Document::where('name','LIKE',$nameFil)->get();
       
        if( count($documentExistsFirstTest) < 1 ){
            $ocrHelper = new OcrHelper($this->portalOcrUploads, $fileName);
            $text = $ocrHelper->extractText();
            $ocrHelper->setFilename($fileName); /* Restore original filename */
            $converted_file = $ocrHelper->convertToPDF();
            // dd($converted_file);
            
            $documentExists = Document::where('name',$fileName)->first();
            if( is_null($documentExists) ){
                $explode = explode('/', $fileName);
                $filteredName = last($explode);
                
                $document = new Document();
                $document->document_type_id = null;
                $document->user_id = 1;
                $document->name = $filteredName;
                $document->name_long = $filteredName;
                $document->owner_user_id = 1;
                $document->save();
                
                $editor_variant = new EditorVariant();
                $editor_variant->document_id = $document->id;
                $editor_variant->variant_number = 1;
                $editor_variant->inhalt = $text;
                $editor_variant->save();
        
                
                $document_dir = public_path() . '/files/documents/'. $document->id.'/' ;
                File::makeDirectory($document_dir, 0777, true);
                // dd($document_dir.$filteredName);
                File::move($fileName,$document_dir . $filteredName);
                $document_upload_original = new DocumentUpload();
                $document_upload_original->editor_variant_id = $editor_variant->id;
                $document_upload_original->file_path = $filteredName;
                $document_upload_original->save();
        
                
                if($fileName != $converted_file && File::exists($fileName) && File::exists($converted_file)){
                    File::move($this->portalOcrUploads . $converted_file, $document_dir . $converted_file);
                    $document_upload_converted = new DocumentUpload();
                    $document_upload_converted->editor_variant_id = $editor_variant->id;
                    $document_upload_converted->file_path = $converted_file;
                    $document_upload_converted->save();          
                }
            }
        }
        
        
    }
}
