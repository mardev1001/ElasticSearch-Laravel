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
use App\DocumentApproval;

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
        $data->url = 'editor';
        if( $modelUpload->document_art == true ){
            $data->form = 'upload';
            $data->url = 'document-upload';
        }
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
    
    /**
     * Process save or update multiple select fiels
     *
     * @return bool
     */
    public function processOrSave($collections,$pluckedCollection,$requests,$modelName,$fields=array(),$notIn=array() ,$tester=null){
        $modelName = '\App\\'.$modelName;
        if( count($collections) < 1 && count($pluckedCollection) < 1 ){
            // dd($fields);
            foreach($requests as $request){
               $model = new $modelName();
                foreach($fields as $k=>$field){
                    if($field == 'inherit')
                        $model->$k = $request;
                    else    
                        $model->$k = $field;
                }
                
                $model->save();
            }
        }
        else{
            //delete all where id not in RequestArray whereNotIn 
           // $modelName::whereNotIn($notIn, $requests); +
            $modelDelete = $modelName::where('id','>',0);
            if( count($notIn) > 0 ){
                             
                foreach($notIn as $n=>$in){
                            
                    $modelDelete->whereNotIn($n,$in);
                }
            }
            
            $modelDelete->delete();
          
            foreach($requests as $request){
               if( !is_array($pluckedCollection) )
                $pluckedCollection = (array) $pluckedCollection;
                if ( !in_array($request, $pluckedCollection)) {
                 
                    $model = new $modelName();
                    foreach($fields as $k=>$field){
                        if($field == 'inherit')
                            $model->$k = $request;
                        else    
                            $model->$k = $field;
                    }
                    $model->save();
                }
            }
            
        }
        
        
    }  
    
     /**
     * Get variant number
     *
     * @return string $string
     */
    public function variantNumber($name){
        $string = explode('-',$name);
        return $string[1];
    }    
     
}
