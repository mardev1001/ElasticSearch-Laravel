<?php
namespace App\Http\Repositories;
/**
 * Created by PhpStorm.
 * User: Marijan
 * Date: 04.05.2016.
 * Time: 11:42
 */

use DB;

use App\Document;
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
    * Generate documents treeview. If no array parameter is present, all documents are read.
    *
    * @param  object array $array
    * @param  bool $tags
    * @param  bool $document
    * @return object array $array
    */
    public function generateTreeview( $array = array(), $tags = false, $document=true ){
        
        /*
        // Bootstrap treeview JSON structure
        {
          text: "Node 1",
          icon: "glyphicon glyphicon-stop",
          selectedIcon: "glyphicon glyphicon-stop",
          color: "#000000",
          backColor: "#FFFFFF",
          href: "#node-1",
          selectable: true,
          state: {
            checked: true,
            disabled: true,
            expanded: true,
            selected: true
          },
          tags: ['available'],
          nodes: [
            {},
            ...
          ]
        }
        */
        
        $treeView = array();
        $documents = Document::all();
        
        if(sizeof($array)) $documents = $array;
        if( $document == true )
            foreach ($documents as $document) {
          
            $node = new \StdClass();
            $node->text = $document->name;
            $node->icon = 'icon-parent';
            $node->href = route('dokumente.show', $document->id);
            if(!$document->documentUploads->isEmpty()){
                
                $node->nodes = array();
                if($tags) $node->tags = array(sizeof($document->documentUploads));  
                
                foreach ($document->documentUploads as $upload) {
                    $subNode = new \StdClass();
                    $subNode->text = basename($upload->file_path);
                    $subNode->icon = 'fa fa-file-o';
                    $subNode->href = "#".$upload->file_path;
    
                    array_push($node->nodes, $subNode);
                }
            }
            
            array_push($treeView, $node);
        }
        else
            foreach ($documents as $data) {
          
            $node = new \StdClass();
            $node->text = basename($data->file_path);
            $node->icon = 'fa fa-file-o';
            $node->href = "#".$data->file_path;
            
            array_push($treeView, $node);
        }
        return json_encode($treeView);
        
    }
    
    /**
     * Get redirection form
     *
     * @return string $form
     */
    public function setDocumentForm($documentType, $pdf = false,$attachment=false ){
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
    public function processOrSave($collections,$pluckedCollection,$requests,$modelName,$fields=array(),$notIn=array() ,$tester=false){
        $modelName = '\App\\'.$modelName;
        if( count($collections) < 1 && count($pluckedCollection) < 1 ){
            if($tester == true){
                //  dd($requests);
                $array = array();
            }
            foreach($requests as $request){
               $model = new $modelName();
                foreach($fields as $k=>$field){
                    if($field == 'inherit')
                        $model->$k = $request;
                    else    
                        $model->$k = $field;
                }
                
                $model->save();
                if($tester == true)
                    $array[] = $model;
            }
            // if($tester == true)
              //  var_dump($array);
        }
        else{
           \DB::enableQueryLog();
            $modelDelete = $modelName::where('id','>',0);
            if( count($notIn) > 0 ){
                             
                foreach($notIn as $n=>$in){
                    $modelDelete->whereIn($n,$in);
                   /* if($tester == true){
                        var_dump($n);
                        var_dump($in);
                        echo '<hr/>';
                    }*/
                        
                }
            }
            $modelDelete->delete();
            if($tester == true){
                // dd( \DB::getQueryLog() );
                //var_dump($requests);
                //var_dump($modelDelete->get());
               // echo '<hr/>';
                //echo '<hr/>';
            }
            if( count($requests) > 0){
               
                foreach($requests as $request){
                   //if( !is_array($pluckedCollection) )
                    //$pluckedCollection = (array) $pluckedCollection;
                    //if ( !in_array($request, $pluckedCollection)) {
                     
                        $model = new $modelName();
                        foreach($fields as $k=>$field){
                            if($field == 'inherit')
                                $model->$k = $request;
                            else    
                                $model->$k = $field;
                        }
                        $model->save();
                    }
                //}
                /* if($tester == true)
                    dd( \DB::getQueryLog() );*/
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
