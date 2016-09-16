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

use App\User;
use App\MandantUser;
use App\MandantUserRole;
use App\Document;
use App\DocumentType;
use App\DocumentApproval;
use App\DocumentCoauthor;
use App\PublishedDocument;
use App\UserReadDocument;
use App\EditorVariant;

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
            'pageDocuments' => false,
            'myDocuments' => false,
            'formulare' => false,
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
                
                // Treeview Setting for Homepage
                if ($options['pageHome'] == true || $options['pageFavorites'] == true) {
                    // if($document->document_status_id == 1)
                        // dd($document);
                    if($document->published || ($document->document_status_id == 1 && $options['myDocuments'] == true)){
                        $node->beforeText = '';
                        if($options['pageHome'] == true && $options['myDocuments'] == true)
                            $node->beforeText = 'Version '.$document->version.', '.$document->documentStatus->name.' - ';// Version 3, Entwurf
                            
                        $node->beforeText .= Carbon::parse($document->date_published)->format('d.m.Y').' - ';
                        if(isset($document->owner))
                            $node->beforeText .= $document->owner->first_name.' '.$document->owner->last_name;
                        
                        if($document->published != null)
                            $readDocument = UserReadDocument::where('user_id', Auth::user()->id)
                            ->where('document_group_id', $document->published->document_group_id)->orderBy('id', 'desc')->first();
                        
                        if($document->document_status_id == 3){
                            if(isset($readDocument)) 
                                $icon = 'icon-read ';
                            else
                                $icon = 'icon-notread ';
                        }
                            
                        if($options['pageFavorites'] == true){
                            $node->hrefDelete = url('dokumente/' . $document->id. '/favorit');
                            // $node->icon2 = 'icon-trash ';
                            // $icon2 = 'icon-trash ';
                        }    
                    }
                    
                    $node->afterText = $document->documentType->name;
                    
                }
                
                // Treeview Setting for Document Overview Pages
                if ($options['pageDocuments'] == true) {
                       
                    $node->beforeText = '';
                    $node->beforeText .= Carbon::parse($document->date_published)->format('d.m.Y');
                        if(isset($document->owner)) $node->beforeText .= ' - '.$document->owner->first_name .' '. $document->owner->last_name;
                    
                    if($document->published != null)
                        $readDocument = UserReadDocument::where('user_id', Auth::user()->id)
                        ->where('document_group_id', $document->published->document_group_id)->orderBy('id', 'desc')->first();
                    
                    if($document->document_status_id == 3){ 
                        if(isset($readDocument)) $icon = 'icon-read ';
                        else $icon = 'icon-notread ';
                    }    
                    
                    $node->afterText = $document->documentType->name;
                    
                    if( isset($options['formulare']) && $options['formulare'] == true ){
                        
                        $variants = $this->documentVariantPermission($document, false)->variants;
                        
                        $links = null;
                        // $document->variantDocuments->editorVariant
                        // foreach($document->variantDocuments as $key => $dc){
                        // if(count($document->variantDocuments))
                        // dd($document->variantDocuments);
                        // dd($document->variantDocuments);
                        foreach($document->variantDocuments as $key => $dc){
                            
                            $docPublished = $dc->editorVariant->document->published;
                            if(isset($docPublished)) $docStatus = $docPublished->document->document_status_id == 3;
                            
                            if($key != 0 && $docStatus && isset($docPublished->url_unique)) {
                                $links .= trim('<a href="/dokumente/'. $docPublished->url_unique . '" target="_blank" class="link-after-text">'. $dc->editorVariant->document->name .'</a>') .'; ';
                            }
                        }
                        $node->afterLink = $links;
                        // if(isset($links)) dd($links);
                    }
                }
                
                if ($options['pageHistory'] == true) {
                    // $node->text = "Version " . $document->version . "- " . $node->text . " - " . $document->updated_at;
                    
                    $node->beforeText = '';
                    $node->beforeText = 'Version '.$document->version.', '.$document->documentStatus->name.' - ';// Version 3, Entwurf
                    $node->beforeText .= Carbon::parse($document->date_published)->format('d.m.Y').' - '.$document->owner->first_name.' '.$document->owner->last_name;
                    
                    if($document->published != null)
                        $readDocument = UserReadDocument::where('user_id', Auth::user()->id)
                        ->where('document_group_id', $document->published->document_group_id)->orderBy('id', 'desc')->first();
                
                    $node->afterText = $document->documentType->name;
                    
                    
                     
                    if($document->published) $icon = 'icon-released ';
                    else $icon = 'icon-notreleased ';
                    
                }

                
                if ($document->document_status_id == 3) {
                    
                    if ($document->created_at->gt(Auth::user()->last_login_history)){
                        if( $options['pageFavorites'] == false )
                            $icon2 = 'icon-favorites ';
                    }

                    if($this->canViewHistory()){
                        if ($options['showHistory'] == true) {
                            if (PublishedDocument::where('document_group_id', $document->document_group_id)->count() > 1){
                                $node->hrefHistory = url('dokumente/historie/' . $document->id);
                            }
                        }
                    }
                    
                }

                if (in_array($document->document_status_id, [2,6])) {
                    if($options['pageHome'] == true) {
                        $node->beforeText = '';
                        $node->beforeText .= Carbon::parse($document->date_published)->format('d.m.Y');
                        if(isset($document->owner))
                            $node->beforeText .= ' - '.$document->owner->first_name.' '.$document->owner->last_name;
                    }
                    
                    if($document->document_status_id == 2)
                        $icon = 'icon-open ';
                    else
                        $icon = 'icon-blocked ';
                        
                    $icon2 = 'icon-notreleased ';
                }

                $node->icon = $icon;
                $node->icon2 = $icon2;
                
                // $node->icon3 = $icon3 . 'last-node-icon ';
                // if ($options['showUniqueURL'] == true)
				
                if ($document->document_status_id == 3){
					if($document->published) 
						$node->href = route('dokumente.show', $document->published->url_unique);
				} 
					
                elseif($document->document_status_id == 6)
					$node->href = url('dokumente/'. $document->id .'/freigabe');
                else 
					$node->href = route('dokumente.show', $document->id);

                // TreeView Delete Option - Uncomment if needed
                if ($options['pageFavorites'] && $options['showDelete']){
                     $node->hrefDelete = url('dokumente/' . $document->id. '/favorit');
                     $node->text = $document->name;
                     if($document->document_type_id == 3 ){
                       if($document->qmr_number != null)
                            $node->text = $document->qmr_number.$document->additional_letter .": ". $node->text;
                         $node->text = "QMR ". $node->text;
                    }
                    //  $icon2 = 'icon-trash';
                    //  $node->icon2 = 'icon-trash ';
                }
                
                if ($document->document_status_id != 6) {
                    
                    $variants = $this->documentVariantPermission($document, false)->variants;
                    // if (sizeof($document->editorVariantNoDeleted)) {
                    if (count($variants)) {
                        
                        // get all variants, and all their attachments
                        
                        $documentsAttached = array();
                       
                        foreach($variants as $ev){
                            if($ev->hasPermission == true){
                                foreach($ev->editorVariantDocument as $evd){
                                    array_push($documentsAttached, Document::find($evd->document_id));
                                }
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
                                        $subNode->icon = 'child-node hidden ';
                                        $subNode->icon2 = 'icon-download ';
                                        $subNode->text = $secondDoc->name;
                                        // $subNode->text = $upload->file_path;
                                        $subNode->href = '/download/' . $secondDoc->id . '/' . $upload->file_path;
                                        array_push($node->nodes, $subNode);
                                    }
                                    
                                }
                            }
                            
                            if(count($node->nodes)<1)
                                unset($node->nodes);
                        }
                    }
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
                                $subNode->child = true;

                                array_push($node->nodes, $subNode);
                            }
                                
                            if(count($node->nodes)<1)
                                unset($node->nodes);
                        }

                        array_push($treeView, $node);
                        
                    }
                // }//if second doc not null
            }

        }
        
        return json_encode($treeView);

    }
    
    /**
     * Generate wiki entry treeview. If no array parameter is present, all documents are read.
     *
     * @param  object array $array
     * @param  bool $tags
     * @param  bool $document
     * @return object array $array
     */
    
    public function generateWikiTreeview($items = array())
    {
        $treeView = array();
        $wikiPages = array();
        
        if (sizeof($items)) $wikiPages = $items;
    
        foreach ($wikiPages as $wikiPage) {
            
            $node = new \StdClass();
            $node->text = $wikiPage->name;
            // $icon = $icon2 = '';
                   
            $node->beforeText = '';
            $node->beforeText .= Carbon::parse($wikiPage->date_created)->format('d.m.Y').' - '.
                $wikiPage->user->first_name .' '. $wikiPage->user->last_name;
            
            $node->afterText = $wikiPage->category->name;
        
            // $node->icon = $icon;
            // $node->icon2 = $icon2;
            
            $node->href = url('/wiki/' . $wikiPage->id);
            
            array_push($treeView, $node);
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
                    // dd($options['documentId']);
                    if ($evd->document_id != null && $options['documentId'] != 0 && $evd->document_id != $options['documentId']) {
     
                        $secondDoc = Document::find($evd->document_id);
                        $node = new \StdClass();
                        $node->name = $secondDoc->name.' ('.$secondDoc->documentStatus->name .')';
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
        if ($modelUpload->document_art == true || $documentType == 5) {
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
    
     /**
     * Check if user is Historien Leser
     * @return bool 
     */
    public function canViewHistory(){
        $uid = Auth::user()->id;
        $mandantUsers =  MandantUser::where('user_id',$uid)->get();
        foreach($mandantUsers as $mu){
            $userMandatRoles = MandantUserRole::where('mandant_user_id',$mu->id)->get();
            foreach($userMandatRoles as $umr){
                if( $umr->role_id == 14)
                    return true;
            }
        }
        return false;
    }
    
    
    /**
     * detect if model is dirty or not
     * @return bool 
     */
    public function clearUsers($users){
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
     * Universal document permission check
     * @param array $userArray
     * @param collection $document
     * @param bool $message
     * @return bool || response
     */
    public function universalDocumentPermission( $document, $message=true, $freigeber=false, $filterAuthors=false ){
        $uid = Auth::user()->id;
        $mandantUsers =  MandantUser::where('user_id',$uid)->get();
        $role = 0;
        $hasPermission = false;
         
        
        foreach($mandantUsers as $mu){
            $userMandatRole = MandantUserRole::where('mandant_user_id',$mu->id)->first();
            if( $userMandatRole != null && $userMandatRole->role_id == 1 )
                $hasPermission = true ;
               
        }
        if( $freigeber == true ){
            $documentAprrovers = DocumentApproval::where('document_id', $document->id)->where('user_id',$uid)->get();
            if( count($documentAprrovers) )
                $hasPermission = true;
               
        }
        $coAuthors = DocumentCoauthor::where('document_id',$document->id)->pluck('user_id')->toArray();
        if( $uid == $document->user_id  || $uid == $document->owner_user_id || in_array($uid, $coAuthors) 
        || ( $freigeber == false && $filterAuthors == false && $document->approval_all_roles == 1) || $role == 1 )
           $hasPermission = true; 
           
        if( $message == true  && $hasPermission == false)
            session()->flash('message',trans('documentForm.noPermission'));
        //if($document->id == 118)
        //    dd($hasPermission);
        return $hasPermission;
    }
    
    /**
     * Document variant permission
     * @param collection $document
     * @return object $object 
     */
    public function documentVariantPermission($document,$message=true){
        
        /*  class $object stores 2 attributes: 
            1. permissionExists( this is a global hasPermissionso we dont have to iterate again to see if permission exists  )
            2. variants (to store variants)[duuh]
        */
        $uid = Auth::user()->id;
        $object = new \StdClass(); 
        $object->permissionExists = false;
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id',Auth::user()->id)->pluck('mandant_id');
        $madantArr = $mandantUserMandant->toArray();
        $mandantIdArr = $mandantId->toArray();
        $mandantRoles =  MandantUserRole::whereIn('mandant_user_id',$mandantId)->pluck('role_id');
        $mandantRolesArr =  $mandantRoles->toArray();
        
        $variants = EditorVariant::where('document_id',$document->id)->get();
        $hasPermission = false;
        
        foreach($variants as $variant){
            if($hasPermission == false){//check if hasPermission is already set
                if($variant->approval_all_mandants == true){//database check
                    
                    if($document->approval_all_roles == true){//database check
                            $hasPermission = true;
                            $variant->hasPermission = true;
                            $object->permissionExists = true;
                        }
                        else{
                            foreach($variant->documentMandantRoles as $role){// if not from database then iterate trough roles
                                if( in_array($role->role_id, $mandantRolesArr) ){//check if it exists in mandatRoleArr
                                 $variant->hasPermission = true;
                                 $hasPermission = true;
                                 $object->permissionExists = true;
                                }
                            }//end foreach documentMandantRoles
                        }
                }
                else{
                   
                    foreach( $variant->documentMandantMandants as $mandant){
                            //   if( $document->id == 3  && $uid == 17)
                            //         dd($mandantRolesArr);
                        if( $this->universalDocumentPermission($document,$message) == true){
                          
                           if($document->approval_all_roles == true){
                                foreach($variant->documentMandantMandants as $mandant){
                                    if( in_array($mandant->mandant_id, $madantArr) ){
                                        $variant->hasPermission = true;
                                        $hasPermission = true;
                                        $object->permissionExists = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                            else{
                                foreach($variant->documentMandantRoles as $role){
                                    if( in_array($role->role_id, $mandantRolesArr) ){
                                        
                                        $variant->hasPermission = true;
                                        $hasPermission = true;
                                        $object->permissionExists = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                        }
                        else if( in_array($mandant->mandant_id,$mandantRolesArr) ){
                            
                            if($document->approval_all_roles == true){
                                $hasPermission = true;
                                $variant->hasPermission = true;
                                $object->permissionExists = true;
                            }
                            else{
                                foreach($variant->documentMandantRoles as $role){
                                   
                                    if( in_array($role->role_id, $mandantRolesArr) ){
                                        
                                        $variant->hasPermission = true;
                                        $hasPermission = true;
                                        $object->permissionExists = true;
                                    }
                                }//end foreach documentMandantRoles
                            }
                        }
                    }//end foreach documentMandantMandants
                }
            }
        }
        if( $object->permissionExists == true )
            \Session::forget('message');
        $object->variants = $variants;
        return $object;
    }//end documentVariant permission
   
    public function getUserPermissionedDocuments($collection,$paginator='page'){
    	foreach($collection as $key => $document){
		    if($this->documentVariantPermission($document,false)->permissionExists == false)
		        unset($collection[$key]);
		    
		}
		$documentsNewArr = $collection->pluck('id')->toArray();
		
        
        $collection = Document::whereIn('id',$documentsNewArr)->orderBy('documents.id', 'desc')->paginate(10, ['*'], $paginator);
        // dd($collection);
        return $collection;
    }
}
