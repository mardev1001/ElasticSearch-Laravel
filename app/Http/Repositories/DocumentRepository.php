<?php
namespace App\Http\Repositories;
/**
 * Created by PhpStorm.
 * User: Marijan
 * Date: 04.05.2016.
 * Time: 11:42
 */

use DB;

use App\DocumentType;

class DocumentRepository
{
   /**
    * Generate dummy data
    *
    * @return object array $array
    */
    public function generateDummyData($name ='',$collections=array(), $tags = true ){
        $array = array();
        for( $i=0; $i<rand(1,10);$i++ ){
            $data = new \StdClass();
            $data->text = $name.'-'.rand(1,200);
            if( $tags == true )
                $data->tags = array(count( $collections ) );
            
            if( count($collections) > 0)
                $data->nodes = $collections;
            array_push($array, $data);
        }
         return $array;
    }
    
    /**
     * Get redirection form
     *
     * @return string $form
     */
    public function setDocumentForm($documentType, $pdf = false ){
        $data = new \StdClass();
        $modelUpload = DocumentType::find($documentType);
        $data->form = 'editor';
        if( $modelUpload->document_art == true ){
            $data->form = 'upload';
            $data->url = 'document-upload';
        }
            $data->form = 'upload';
        if( $pdf == 1 )
            $data = $this->checkUploadType($data,$modelUpload, $pdf);
            
        return $data;
    }    
    
    /**
     * Check if document type Round
     *
     * @return string $form
     */
    public function checkUploadType($data,$model, $pdf){
        if( (strpos( strtolower($model->name) , 'rundschreiben') !== false) && ( $pdf == true || $pdf == 1) ){
            $data->form = 'pdfUpload';
            $data->url = 'pdf-upload';
        }
        
        return $data;
    }    
     
}
