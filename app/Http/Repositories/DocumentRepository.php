<?php
namespace App\Http\Repositories;
/**
 * Created by PhpStorm.
 * User: Marijan
 * Date: 04.05.2016.
 * Time: 11:42
 */

use DB;


use Auth;
use Carbon\Carbon;

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
    
    public function generateDummyDataSingle($name ='',$collections=array(), $tags = true ){
        $array = array();
        for( $i=0; $i<1;$i++ ){
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
    public function generateTreeview( $array = array(), $tags = false, $document=true,$documentId=0 ){
        
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
        
        /*
        @each $el in favorites, blocked, open, notread, read, notreleased, released, history, download, goto, comment, legend, arrow {
          .icon-#{$el} {
            background: url('/img/icons/icon_#{$el}.png') no-repeat;
          }
        }
        */
        
        // dd(json_encode($this->generateDummyData('Mein Kommentar', $this->generateDummyDataSingle('Kommentar Text Lorem Ipsum Dolor Sit Amet'))));
        
        $treeView = array();
        $documents = Document::all();
        $documents = array();
        if(sizeof($array)) $documents = $array;
        if( $document == true  && count($documents) > 0)
            foreach ($documents as $document) {
        //   dd($documents[2]->documentUploads);
            $node = new \StdClass();
            
            $node->text = $document->name;
            
            $icon =  $icon2 = $icon3 ='';

            // Define icon classes
            // var_dump(Carbon::parse(Auth::user()->last_login)->gt(Carbon::parse($document->created_at)));
            if($document->document_status_id == 3){
                if(Carbon::parse(Auth::user()->last_login)->lt(Carbon::parse($document->created_at)))
                    $icon = 'icon-favorites ';
                // $icon2 = 'icon-open ';
                $icon3 = 'icon-history ';
            }
            
            // dd(Carbon::parse(Auth::user()->last_login)->lt(Carbon::parse($document->created_at)));
            if($document->document_status_id == 6){
                $icon = 'icon-blocked ';
                $icon2 = 'icon-notreleased ';
                // $icon3 = 'icon-history ';w
            }
            
            $node->icon = $icon;
            $node->icon2 = $icon2;
            $node->icon3 = $icon3 . 'last-node-icon ';
            
            $node->href = route('dokumente.show', $document->id);
            
            
            if($document->document_status_id != 6){
                if(!$document->documentUploads->isEmpty()){
                    
                    $node->nodes = array();
                    if($tags) $node->tags = array(sizeof($document->documentUploads));  
                    
                    foreach ($document->documentUploads as $upload) {
                        $subNode = new \StdClass();
                        $subNode->text = basename($upload->file_path);
                        $subNode->icon = 'child-node ';
                        $subNode->icon2 = 'icon-download ';
                        $subNode->icon3 = 'last-node-icon ';
                        $subNode->href = 'download/'.str_slug($document->name).'/'.$upload->file_path;
                        
        
                        array_push($node->nodes, $subNode);
                    }
                }
            }
            
            array_push($treeView, $node);
        }
        elseif( $document == false  && count($documents) > 0){
            foreach($documents->editorVariantDocument as $evd){
                    if( $evd->document_id != null && $documentId != 0 && $evd->document_id != $documentId){
                        $secondDoc = Document::find($evd->document_id);
                        $node = new \StdClass();
                        $node->text = $secondDoc->name;
                        $node->icon = 'icon-parent';
                        //$node->href = route('dokumente.show', $secondDoc->id);
                        
                        if(!$secondDoc->documentUploads->isEmpty()){
                            
                            $node->nodes = array();
                            if($tags) $node->tags = array(sizeof($secondDoc->documentUploads));  
                            
                            foreach ($secondDoc->documentUploads as $upload) {
                                $subNode = new \StdClass();
                                $subNode->text = basename($upload->file_path);
                                $subNode->icon = 'fa fa-file-o';
                                $subNode->href = 'download/'.str_slug($secondDoc->name).'/'.$upload->file_path;
                
                                array_push($node->nodes, $subNode);
                            }
                        }
                        
                        array_push($treeView, $node); 
                    }
                  
                }
                
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
        // dd($pdf);
        if( $pdf == 1 || $pdf == "1"  || $pdf == true)
            $data = $this->checkUploadType($data,$modelUpload, $pdf);
        
        return $data;
    }    
    
    /**
     * Check if document type Round
     *
     * @return string $form
     */
    public function checkUploadType($data,$model, $pdf){
        if( ( (strpos( strtolower($model->name) , 'rundschreiben') !== false) || (strpos( strtolower($model->name) , 'news') !== false) ) 
        && ( $pdf == true || $pdf == 1) ){
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
