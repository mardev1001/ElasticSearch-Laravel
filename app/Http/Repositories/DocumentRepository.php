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
use App\PublishedDocument;
use App\UserReadDocument;

class DocumentRepository
{
    /**
     * Generate dummy data
     *
     * @return object array $array
     */
    public function generateDummyData($name = '', $collections = array(), $tags = true)
    {
        $array = array();
        for ($i = 0; $i < rand(1, 10); $i++) {
            $data = new \StdClass();
            $data->text = $name . '-' . rand(1, 200);
            if ($tags == true)
                $data->tags = array(count($collections));

            if (count($collections) > 0)
                $data->nodes = $collections;
            array_push($array, $data);
        }
        return $array;
    }

    public function generateDummyDataSingle($name = '', $collections = array(), $tags = true)
    {
        $array = array();
        for ($i = 0; $i < 1; $i++) {
            $data = new \StdClass();
            $data->text = $name . '-' . rand(1, 200);
            if ($tags == true)
                $data->tags = array(count($collections));

            if (count($collections) > 0)
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
    // public function generateTreeview( $items = array(), $tags = false, $document=true, $documentId=0, $hrefDelete=false ){
    public function generateTreeview($items = array(), $options = array())
    {

        $optionsDefault = [
            'tags' => false,
            'document' => true,
            'documentId' => 0,
            'showApproval' => false,
            'showUniqueURL' => false,
            'showDelete' => false,
            'showHistory' => false,
            'pageHome' => false,
            'pageHistory' => false,
            'pageWiki' => false,
            'pageFavorites' => false,
        ];
        $options = array_merge($optionsDefault, $options);

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
        $documents = array();
        
        if (sizeof($items)) $documents = $items;

        if ($options['document'] == true && count($documents) > 0) {
            foreach ($documents as $document) {
                
                $node = new \StdClass();
                $node->text = $document->name;
                $icon = $icon2 = '';
                
                if($document->document_type_id == 3 ){
                    if($document->qmr_number != null)
                        $node->text = $document->qmr_number.$document->additional_letter .": ". $node->text;
                    $node->text = "QMR ". $node->text;
                }
                if ($options['pageHome'] == true || $options['pageFavorites'] == true) {
                    
                    if($document->published){
                        $node->beforeText = Carbon::parse($document->date_published)->format('d.m.Y');
                        
                        $readDocument = UserReadDocument::where('user_id', Auth::user()->id)
                        ->where('document_group_id', $document->published->document_group_id)->orderBy('id', 'desc')->first();
                        
                        if($readDocument) 
                            $icon = 'icon-read ';
                            // dd($readDocument);
                        else
                            $icon = 'icon-notread ';
                            
                        if($options['pageFavorites'] == true){
                            $node->hrefDelete = url('dokumente/' . $document->id. '/favorit');
                            $icon2 = 'icon-trash';
                        }    
                    }
                    $node->afterText = $document->documentType->name;
                    
                }
                
                if ($options['pageHistory'] == true) {
                    $node->text = "Version " . $document->version . "- " . $node->text . " - " . $document->updated_at;
                }

                
                if ($document->document_status_id == 3) {
                    if (Carbon::parse(Auth::user()->last_login)->lt(Carbon::parse($document->created_at))){
                        // $icon = 'icon-favorites ';
                        if( $options['pageFavorites'] == false )
                            $icon2 = 'icon-favorites ';
                        // $icon2 = 'icon-open ';
                    }

                    if ($options['showHistory'] == true) {
                        if (PublishedDocument::where('document_group_id', $document->document_group_id)->count() > 1){
                            // $icon3 = 'icon-history ';
                            $node->hrefHistory = url('dokumente/historie/' . $document->id);
                        }
                    }
                    
                }

                if ($document->document_status_id == 6) {
                    $icon = 'icon-blocked ';
                    $icon2 = 'icon-notreleased ';
                    // $icon3 = 'icon-history ';
                }

                $node->icon = $icon;
                $node->icon2 = $icon2;
                // $node->icon3 = $icon3 . 'last-node-icon ';
                // if ($options['showUniqueURL'] == true)
                if ($document->document_status_id == 3) $node->href = route('dokumente.show', $document->published->url_unique);
                elseif($document->document_status_id == 6) $node->href = url('dokumente/'. $document->id .'/freigabe');
                else $node->href = route('dokumente.show', $document->id);

                // TreeView Delete Option - Uncomment if needed
                 if ($options['pageFavorites'] && $options['showDelete']){
                     $node->hrefDelete = url('dokumente/' . $document->id. '/favorit');
                     $node->text = $document->name;
                     $icon2 = 'icon-trash';
                     $node->icon2;
                 }
                if ($document->document_status_id != 6) {

                    //
                    if ($options['pageHistory'] == true  ) {
                        
                        $node->nodes = array();

                        foreach ($document->editorVariantOrderBy as $variant) {
                            $subNode = new \StdClass();
                            // $subNode->href = url('dokumente/editor/' . $document->id . '/edit#variation' . $variant->variant_number);
                           
                            $subNode->text = "Variante " . $variant->variant_number;
                            $subNode->icon = 'child-node ';
                            $subNode->icon2 = 'fa fa-2x fa-file-o ';
                            // $subNode->icon3 = $icon3 . 'last-node-icon ';
                          
                            if(count($variant->documentUpload)){
                                  
                                $subNode->nodes = array();
                                foreach ($variant->documentUpload as $upload) {
                                    // if($variant->name = 'pdfrund')
                                    //     dd($upload);
                                    $subSubNode = new \StdClass();
                                    // $subSubNode->text = basename($upload->file_path);
                                    $subSubNode->text = 'PDF Rundschreiben';
                                    $subSubNode->icon = 'sub-child-node ';
                                    $subSubNode->icon2 = 'icon-download ';
                                    // $subSubNode->icon3 = 'last-node-icon ';
                                    // $subSubNode->href = '/download/' . str_slug($document->name) . '/' . $upload->file_path;
                                    $subSubNode->href = '/download/' . $document->id . '/' . $upload->file_path;
                                    
                                    array_push($subNode->nodes, $subSubNode);
                                }
                            }
                            array_push($node->nodes, $subNode);
                        }
                    } elseif (sizeof($document->editorVariantNoDeleted)) {
                        
                        
                        // get all variants, and all their attachments
                        
                        $documentsAttached = array();
                        
                        foreach($document->editorVariantNoDeleted as $ev){
                            foreach($ev->editorVariantDocument as $evd){
                                array_push($documentsAttached, Document::find($evd->document_id));
                            }
                        }
                        
                        // generate item for treeview and add his attachments
                        
                        if(count($documentsAttached)){
                            
                            $node->nodes = array();
                            // $node->icon .= ' parent-node ';
                            
                            // if ($options['tags']) $node->tags = array(sizeof($document->editorVariantDocument));
    
                            foreach ($documentsAttached as $secondDoc) {
                                
                                // $node->href = route('dokumente.show', $secondDoc->id);
            
                                if (!$secondDoc->documentUploads->isEmpty()) {
                                    
                                    // $subNode->nodes = array();
                                    
                                    foreach ($secondDoc->documentUploads as $upload) {
                                        $subNode = new \StdClass();
                                        $subNode->icon = 'child-node ';
                                        $subNode->icon2 = 'icon-download ';
                                        $subNode->text = $secondDoc->name;
                                        // $subNode->text = $upload->file_path;
                                        $subNode->href = '/download/' . $secondDoc->id . '/' . $upload->file_path;
                                        array_push($node->nodes, $subNode);
                                    }
                                    
                                }
                            }
                        }
                    }
                    // } elseif (!$document->documentUploads->isEmpty()) {

                    //     $node->nodes = array();
                    //     if ($options['tags']) $node->tags = array(sizeof($document->documentUploads));

                    //     foreach ($document->documentUploads as $upload) {
                    //         $subNode = new \StdClass();
                    //         // $subNode->text = basename($upload->file_path);
                    //         $subNode->text = 'PDF Rundschreiben';;
                    //         $subNode->icon = 'child-node ';
                    //         $subNode->icon2 = 'icon-download ';
                    //         // $subNode->icon3 = 'last-node-icon ';
                    //         // $subNode->href = '/download/' . str_slug($document->name) . '/' . $upload->file_path;
                    //         $subNode->href = '/download/' . $document->id . '/' . $upload->file_path;


                    //         array_push($node->nodes, $subNode);
                    //     }
                    // }
                }
                array_push($treeView, $node);
            }
        } 
        elseif ($options['document'] == false && count($documents) > 0) {
          
            foreach ($documents->editorVariantDocument as $evd) {
                if (Document::find($evd->document_id) != null)
                    if ($evd->document_id != null && $options['documentId'] != 0 && $evd->document_id != $options['documentId']) {
                        $secondDoc = Document::find($evd->document_id);
                        // if($secondDoc != null){
                        $node = new \StdClass();
                        $node->text = $secondDoc->name;
                        $node->icon = 'icon-parent';
                        
                        // TreeView Delete Option - Uncomment if needed
                        // if ($options['showDelete']){
                        //     $node->hrefDelete = url('anhang-delete/' . $options['documentId']. '/' .$evd->editor_variant_id . '/' .$evd->document_id );
                        // }
                        //$node->href = route('dokumente.show', $secondDoc->id);

                        if (!$secondDoc->documentUploads->isEmpty()) {

                            $node->nodes = array();
                            if ($options['tags']) $node->tags = array(sizeof($secondDoc->documentUploads));

                            foreach ($secondDoc->documentUploads as $upload) {
                                $subNode = new \StdClass();
                                // $subNode->text = basename($upload->file_path);
                                $subNode->text = 'PDF Rundschreiben';;
                                $subNode->icon = 'fa fa-file-o';
                                // $subNode->href = '/download/' . str_slug($secondDoc->name) . '/' . $upload->file_path;
                                $subNode->href = '/download/' . $secondDoc->id . '/' . $upload->file_path;

                                array_push($node->nodes, $subNode);
                            }
                        }

                        array_push($treeView, $node);
                    }
                // }//if second doc not null
            }

        }
        
        return json_encode($treeView);

    }
    
    /**
     * Generate link list of attached documents for the passed item(s)
     *
     * @param  object array $items
     * @param  int $id
     * @return object array $resultArray
     */
    
    public function getAttachedDocumentLinks($items = array(), $id = 0)
    {
        $options = [
            'document' => false,
            'documentId' => $id,
        ];
    
        $documents = array();
        $resultArray = array();
    
        if (sizeof($items)) $documents = $items;
    
        if (count($documents) > 0) {
            foreach ($documents->editorVariantDocument as $evd) {
                if (Document::find($evd->document_id) != null) {
                    if ($evd->document_id != null && $options['documentId'] != 0 && $evd->document_id != $options['documentId']) {
    
                        $secondDoc = Document::find($evd->document_id);
                        $node = new \StdClass();
                        $node->name = $secondDoc->name;
                        $node->documentId = $secondDoc->id;
                        $node->deleteUrl = url('anhang-delete/' . $options['documentId'] . '/' . $evd->editor_variant_id . '/' . $evd->document_id);
    
                        //$node->href = route('dokumente.show', $secondDoc->id);
    
                        if (!$secondDoc->documentUploads->isEmpty()) {
                            
                            $node->files = array();
                            
                            foreach ($secondDoc->documentUploads as $upload) {
                                $subNode = new \StdClass();
                                // $subNode->text = basename($upload->file_path);
                                // $subNode->downloadUrl = '/download/' . str_slug($secondDoc->name) . '/' . $upload->file_path;
                                $subNode->downloadUrl = '/download/' . $secondDoc->id . '/' . $upload->file_path;
                                array_push($node->files, $subNode);
                            }
                            
                            array_push($resultArray, $node);
                        }
                    }                
                }
            }
        }
        
        // dd($resultArray);
        return $resultArray;
    
    }

    /**
     * Get redirection form
     *
     * @return string $form
     */
    public function setDocumentForm($documentType, $pdf = false, $attachment = false)
    {
        $data = new \StdClass();
        $modelUpload = DocumentType::find($documentType);
        $data->form = 'editor';
        $data->url = 'editor';
        if ($modelUpload->document_art == true) {
            $data->form = 'upload';
            $data->url = 'document-upload';
        }
        // dd($pdf);
        if ($pdf == 1 || $pdf == "1" || $pdf == true)
            $data = $this->checkUploadType($data, $modelUpload, $pdf);

        return $data;
    }

    /**
     * Check if document type Round
     *
     * @return string $form
     */
    public function checkUploadType($data, $model, $pdf)
    {
        if (((strpos(strtolower($model->name), 'rundschreiben') !== false) || (strpos(strtolower($model->name), 'news') !== false))
            && ($pdf == true || $pdf == 1)
        ) {
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
    public function processOrSave($collections, $pluckedCollection, $requests, $modelName, $fields = array(), $notIn = array(), $tester = false)
    {
        $modelName = '\App\\' . $modelName;
        if (count($collections) < 1 && count($pluckedCollection) < 1) {
            if ($tester == true) {
                //  dd($requests);
                $array = array();
            }
            foreach ($requests as $request) {
                $model = new $modelName();
                foreach ($fields as $k => $field) {
                    if ($field == 'inherit')
                        $model->$k = $request;
                    else
                        $model->$k = $field;
                }

                $model->save();
                if ($tester == true)
                    $array[] = $model;
            }
            // if($tester == true)
            //  var_dump($array);
        } else {
            // \DB::enableQueryLog();
            $modelDelete = $modelName::where('id', '>', 0);
            if (count($notIn) > 0) {

                foreach ($notIn as $n => $in) {
                    $modelDelete->whereIn($n, $in);
                    /* if($tester == true){
                         var_dump($n);
                         var_dump($in);
                         echo '<hr/>';
                     }*/

                }
            }
            $modelDelete->delete();
            if ($tester == true) {
                // dd( \DB::getQueryLog() );
                //var_dump($requests);
                //var_dump($modelDelete->get());
                // echo '<hr/>';
                //echo '<hr/>';
            }
            if (count($requests) > 0) {

                foreach ($requests as $request) {
                    //if( !is_array($pluckedCollection) )
                    //$pluckedCollection = (array) $pluckedCollection;
                    //if ( !in_array($request, $pluckedCollection)) {

                    $model = new $modelName();
                    foreach ($fields as $k => $field) {
                        if ($field == 'inherit')
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
    public function variantNumber($name)
    {
        $string = explode('-', $name);
        return $string[1];
    }

}
