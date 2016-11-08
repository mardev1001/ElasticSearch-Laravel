<?php
namespace App\Helpers;
use Request;

use Auth;

use App\User;
use App\Mandant;
use App\MandantUser;
use App\MandantUserRole;
use App\PublishedDocument;
use App\DocumentCoauthor;
use App\EditorVariant;
use App\DocumentApproval;

class ViewHelper{
    
   
    /**
     * Generate and check input type text
     *
     * @param string $str
     * @param array $tags
     *
     * @return string $string
     */
    static function stripTags( $str,$tags=array() ){
        foreach($tags as $tag){
          $str=preg_replace('/<'.$tag.'[^>]*>/i', '', $str);
          $str=preg_replace('/<\/'.$tag.'>/i', '', $str);  
        }
        
        return $str;
    }   
    
    /**
     * Generate and check input type text
     *
     * @param string $inputName
     * @param array $data || string $data='' ( declared in FormViewComposer)
     * @param string $old
     * @param string $label
     * @param string $placeholder
     * @param bool $required
     * @return string $value || $old
     */
    static function setInput( $inputName , $data, $old, $label='', $placeholder='', $required=false, $type='' , $classes=array(), $dataTags=array() ){
        $string = '';
        if( $placeholder == '')
            $placeholder = $label;
         
        if( $type == '')
            $type = 'text';
         
            $string = view('partials.inputText',
                    compact('inputName','data', 'type', 'label', 'old', 'placeholder', 'required', 'classes', 'dataTags')
                )->render();
   
     
      echo $string;
    }
    
    /**
     * Generate and check input type checkbox
     *
     * @param string $inputName
     * @param array $data || string $data='' ( declared in FormViewComposer)
     * @param string $old
     * @param string $label
     * @param bool $required
     * @return string $value || $old
     */
    static function setCheckbox( $inputName ,$data , $old, $label='', $required=false, $classes=array(), $dataTags=array(), $number=-1 ){
        $string = '';
        $string = view('partials.inputCheckbox',
                compact('inputName','classes', 'dataTags','data','label','old','required','number')
            )->render();
     
      echo $string;
    }
    
    /**
     * Generate and check input type textarea
     *
     * @param string $inputName
     * @param array $data || string $data='' ( declared in FormViewComposer)
     * @param string $old
     * @param string $label
     * @param string $placeholder
     * @param bool $required
     * @param array $classes
     * @param array $dataAttr
     * @return string $value || $old
     */
    static function setArea( $inputName ,$data , $old, $label='', $placeholder='', $required=false, $classes=array(), $dataTag=array(), $readonly=false, $parseBack = false  ){
        $string = '';
        if( $placeholder == '')
            $placeholder = $label;
         
            $string = view('partials.inputTextarea',
                    compact('inputName','data','label','old','placeholder','required','readonly','parseBack')
                )->render();
   
     
      echo $string;
    }
    
    
    /**
     * Generate and check input type textarea
     *
     * @param array $collections
     * @param string $inputName
     * @param array $data || string $data='' ( declared in FormViewComposer)
     * @param string $old
     * @param string $label
     * @param string $placeholder
     * @param bool $required
     * @param array $classes
     * @param array $dataAttr
     * @return string $value || $old
     */
    static function setSelect($collections=array(), $inputName, $data , $old, $label='', $placeholder='', $required=false, $classes=array(), $dataTag=array(), $attributes=array()  ){
        if( $placeholder == '')
            $placeholder = $label;
            
        if( $old == '' &&  isset( $data->$inputName) && !empty($data->$inputName) )
            $value = $data->$inputName;
            
        $string = '';
        $string = view('partials.inputSelect',
                    compact('collections','inputName','data','label','old','placeholder','required','classes','dataTag','attributes')
                )->render();
       
     
       echo $string;
    }
    
    /**
     * Generate and check input type textarea
     *
     * @param array $collections
     * @param string $inputName
     * @param array $data || string $data='' ( declared in FormViewComposer)
     * @param string $old
     * @param string $label
     * @param string $placeholder
     * @param bool $required
     * @param array $classes
     * @param array $dataAttr
     * @return string $value || $old
     */
    static function setUserSelect($collections=array(), $inputName, $data , $old, $label='', $placeholder='', $required=false, $classes=array(), $dataTag=array(), $attributes=array()  ){
        if( $placeholder == '')
            $placeholder = $label;
            
        if( $old == '' &&  isset( $data->$inputName) && !empty($data->$inputName) )
            $value = $data->$inputName;
            
        $string = '';
        $string = view('partials.inputUserSelect',
                    compact('collections','inputName','data','label','old','placeholder','required','classes','dataTag','attributes')
                )->render();
       
     
       echo $string;
    }
    
    /**
     * Generate and check input type textarea
     *
     * @param object array $userValues
     * @param string $value
     * @echo string 'selected'
     */
    static function setMultipleSelect( $userValues, $value, $key='id'){
        foreach($userValues as $userValue){
            if($userValue->$key == $value )
                echo 'selected ';
        }
     
    }
    
    /**
     * Generate and check input type textarea
     *
     * @param object array $userValues
     * @param string $value
     * @echo string 'selected'
     */
    static function setComplexMultipleSelect($collection,$relationship, $value, $key='id',$oneLessForeach=false,$dontShow=false){
        if($oneLessForeach == false){
            if( count($collection) )
            foreach($collection as $col){
                foreach($col->$relationship as $userValue){
                    if( $userValue->$key == $value )
                       echo 'selected 2';
                }  
            }
            
        }
        else{
            if( count($collection->$relationship) > 0 ){
                foreach($collection->$relationship as $cr){
                     if( $cr->$key == $value )
                        echo 'selected 3';
                }
            }
        }
    }
    
    /**
     * Function that sets "Alle" if no database records found
     *
     * @param object array $userValues
     * @param string $value
     * @return bool $hasRecords
     */ 
    static function countComplexMultipleSelect($collection,$relationship,$oneLessForeach=false){
        $hasRecords = false;
        if($oneLessForeach == false){
            if( count($collection) )
            foreach($collection as $col){
                if( count($col->$relationship) > 0 ){
                      $hasRecords = true;
                }  
            }
        }
        else{
            if( count($collection->$relationship) > 0 ){
                     
                        $hasRecords = true;
                
            }
        }
        return $hasRecords;
    }
    
    /**
     * Echo required font awesome asterisk 
     *
     * @echo string 
     */
    static function asterisk(){
        echo '<i class="fa fa-asterisk text-info"></i>';
    }
    
    /**
     * Echo required font awesome asterisk 
     *
     * @echo string 
     */
    static function incrementCounter($counter){
       return $counter++;
    }
    
    /**
     * Highlight keywords in string
     *
     * @param string $needle
     * @param string $haystack
     * @return string $newstring
     */
    static function highlightKeyword($needle, $haystack) {
        $occurrences = substr_count(strtolower($haystack), strtolower($needle));
        $newstring = $haystack;
        $match = array();
     
        for ($i=0;$i<$occurrences;$i++) {
            $match[$i] = stripos($haystack, $needle, $i);
            $match[$i] = substr($haystack, $match[$i], strlen($needle));
            $newstring = str_replace($match[$i], '[#]'.$match[$i].'[@]', strip_tags($newstring));
        }
     
        $newstring = str_replace('[#]', '<span class="highlight">', $newstring);
        $newstring = str_replace('[@]', '</span>', $newstring);
        return $newstring;    
    }
    
    /**
     * Highlight keywords in string
     *
     * @param string $needles
     * @param string $haystack
     * @return string $newstring
     */
    static function highlightKeywords($needles = array(), $haystack){
        $parsedText = $haystack;
        
        foreach ($needles as $keyword) {
            if(stripos($parsedText, $keyword) !== false ){
                $parsedText = preg_replace("/$keyword/i", "<span class='highlight'>\$0</span>", $parsedText);
            }
        }
        return $parsedText;
    }
    
    /**
     * Return n number of sentences. Default2
     *
     * @param string $body
     * @param int $sentencesToDisplay
     * @return string $newstring
     */
    static function sentencesToDisplay($body, $sentencesToDisplay = 2) {
        $nakedBody = preg_replace('/\s+/',' ',strip_tags($body));
        $sentences = preg_split('/(\.|\?|\!)(\s)/',$nakedBody);
    
        if (count($sentences) <= $sentencesToDisplay)
            return $nakedBody;
    
        $stopAt = 0;
        foreach ($sentences as $i => $sentence) {
            $stopAt += strlen($sentence);
    
            if ($i >= $sentencesToDisplay - 1)
                break;
        }
    
        $stopAt += ($sentencesToDisplay * 2);
        $newString = trim(substr($nakedBody, 0, $stopAt));
        
        return $newString;
    }
    
    
    
    /**
     * Return shortened text extract with keyword parameter
     *
     * @param string $needle
     * @param string $haystack
     * @return string $newstring
     */
    static function extractText($needle, $haystack) {
        if(empty($haystack)) return;
        $newstring = '';
        $haystack = html_entity_decode(strip_tags($haystack));
        $extractLenght = 128;
        $needlePosition = strpos($haystack , $needle);
        $newstring = '... ' . substr($haystack, $needlePosition, 128) . ' ...';
        return strip_tags($newstring);    
    }
    
    /**
     * Return shortened text extract
     *
     * @param string $haystack
     * @return string $newstring
     */
    static function extractTextSimple($haystack){
        if(empty($haystack)) return;
        $haystack = html_entity_decode(strip_tags(trim($haystack)));
        $extractLenght = 128;
        $needlePosition = 0;
        $newstring = substr($haystack, $needlePosition, 128) . ' ...';
        return strip_tags($newstring);
    }
    
    /**
     * Show active/inactive user count 
     *
     * @param array $usersActive
     * @param array $usersInactive
     * @return string $newstring
     */
    static function showUserCount($usersActive, $usersInactive) {
        $newString = view('partials.showUserCount', compact('usersActive','usersInactive'))->render();
        return $newString;
    }
    
    
    /**
     * Show history link if history is available user count 
     *
     * @param Document $document
     * @return string $link
     */
    static function showHistoryLink($document) {
        if(ViewHelper::canViewHistory()){
            if (PublishedDocument::where('document_group_id', $document->document_group_id)->count() > 1)
                return $link = url('dokumente/historie/' . $document->id);
                // return $link = '<a href="'. url('dokumente/historie/' . $document->id) .'" class="link history-link">'. trans('sucheForm.history-available') . '</a>';
        }
    }
    
    
    /**
     * Check if user is Struktur admin Dokumenten Verfasser,Rundschreiben Verfasser
     * @param Collection $document 
     * @param int $uid (user id) 
     * @return object 
     */
    static function canCreateEditDoc(){
        $uid = Auth::user()->id;
        $mandantUsers =  MandantUser::where('user_id',$uid)->get();
        
        foreach($mandantUsers as $mu){
            $userMandatRoles = MandantUserRole::where('mandant_user_id',$mu->id)->get();
            //  dd( $userMandatRoles );
            foreach($userMandatRoles as $umr){
                if( $umr->role_id == 1 || $umr->role_id == 11 || $umr->role_id == 13)
                return true;
            }
        }
        return false;
    }
    
    /**
     * Check if user is Historien Leser
     * @return bool 
     */
    static function canViewHistory(){
        $uid = Auth::user()->id;
        $mandantUsers =  MandantUser::where('user_id',$uid)->get();
        foreach($mandantUsers as $mu){
            $userMandatRoles = MandantUserRole::where('mandant_user_id',$mu->id)->get();
            foreach($userMandatRoles as $umr){
                if( $umr->role_id == 14 || $umr->role_id == 1)
                    return true;
            }
        }
        return false;
    }
    
    /**
     * Check if is 
     * @return bool 
     */
    static function canViewWikiManagmentAdmin(){
        $uid = Auth::user()->id;
        $mandantUsers =  MandantUser::where('user_id',$uid)->get();
        foreach($mandantUsers as $mu){
            $userMandatRoles = MandantUserRole::where('mandant_user_id',$mu->id)->get();
            foreach($userMandatRoles as $umr){
                if( $umr->role_id == 15 || $umr->role_id == 1 )//wiki redaktur
                    return true;
            }
        }
        return false;
    }
    
    /**
     * Generate comment boxes
     * @param Collection $collection 
     * @param string $title
     * @return string $string (html template) 
     */
    static function generateCommentBoxes($collection,$title,$withRow=false){
        $string = '';
        $string = view('partials.comments', compact('collection','title','withRow') )->render();
        echo $string;
    }
    
    /**
     * Universal user has permissions check
     * @param array $userArray
     * @return bool 
     */
    static function universalHasPermission( $userArray=array(), $withAdmin=true, $debug=false){
        $uid = Auth::user()->id;
      
        $mandantUsers =  MandantUser::where('user_id',$uid)->get();
        $hasPermission = false;   
        foreach($mandantUsers as $mu){
            $userMandatRoles = MandantUserRole::where('mandant_user_id',$mu->id)->get();
            foreach($userMandatRoles as $umr){
               if($withAdmin == true){
                    if( $umr->role_id == 1 || in_array($umr->role_id, $userArray) ){
                        
                        $hasPermission = true;
                    }
                }
                else{
                    
                    if( in_array($umr->role_id, $userArray) == true && $umr->role_id != null ){
                        // dd($userMandatRoles);
                        $hasPermission = true;
                    }
                    
                }
                   
            }
        }
    
        return $hasPermission;
    }
    
   /**
     * Universal document permission check
     * @param array $userArray
     * @param collection $document
     * @param bool $message
     * @return bool || response
     */
    static function universalDocumentPermission( $document, $message=true, $freigeber=false, $filterAuthors=false ){
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
        // dd( $freigeber == false && $filterAuthors == false && $document->approval_all_roles == 1 );
        
        if( $uid == $document->user_id  || $uid == $document->owner_user_id || in_array($uid, $coAuthors) 
        || ( $freigeber == false && $filterAuthors == false && $document->approval_all_roles == 1) || $role == 1 )
            $hasPermission = true;
        //   dd($coAuthors); 
           
        if( $message == true  && $hasPermission == false)
            session()->flash('message',trans('documentForm.noPermission'));
        //if($document->id == 118)
            // dd($hasPermission);
        return $hasPermission;
    }
    
    /**
     * Check if user has mandant variant
     * @param collection $document
     * @return int 
     */
    static function userHasMandantVariant( $document ){
        $uid = Auth::user()->id;
        $variants = EditorVariant::where('document_id',$document->id)->get();
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id',Auth::user()->id)->pluck('mandant_id');
        $mandantIdArr = $mandantUserMandant->toArray();
        $hasOwnMandant = 0;
        foreach($variants as $variant){
            foreach( $variant->documentMandantMandants as $mandant){
                if( in_array($mandant->mandant_id,$mandantIdArr) ){
                    $hasOwnMandant = $variant->id;
                }
            }
        }
        return $hasOwnMandant;
    }
    
    /**
     * Document variant permission
     * @param collection $document
     * @return object $object 
     */
    static function documentVariantPermission($document){
        
        /*  class $object stores 2 attributes: 
            1. permissionExists( this is a global hasPermissionso we dont have to iterate again to see if permission exists  )
            2. variants (to store variants)[duuh]
        */
        
        $object = new \StdClass(); 
        $object->permissionExists = false;
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id',Auth::user()->id)->pluck('mandant_id');
        $mandantIdArr = $mandantUserMandant->toArray();
        $mandantRoles =  MandantUserRole::whereIn('mandant_user_id',$mandantId)->pluck('role_id');
        $mandantRolesArr =  $mandantRoles->toArray();
        
        $variants = EditorVariant::where('document_id',$document->id)->get();
        $hasPermission = false;
        
        
        foreach($variants as $variant){
            //  dd($variant);
            if($hasPermission == false){//check if hasPermission is already set
            
                if($variant->approval_all_mandants == true){//database check
            //   dd($variant);
                    // dd($document);
                    if($document->approval_all_roles == true){//database check
                        $hasPermission = true;
                        $variant->hasPermission = true;
                        $object->permissionExists = true;
                    }
                    else{
                        foreach($variant->documentMandantRoles as $role){// if not from database then iterate trough roles
                            if( self::universalDocumentPermission($document,false) == true){
                                $hasPermission = true;
                                $variant->hasPermission = true;
                                $object->permissionExists = true;
                            }
                            else{
                                if( in_array($role->role_id, $mandantRolesArr) ){//check if it exists in mandatRoleArr
                                    $variant->hasPermission = true;
                                    $hasPermission = true;
                                    $object->permissionExists = true;
                                }    
                            }
                            // dd($hasPermission);
                        }//end foreach documentMandantRoles
                    }
                }
                else{
                    foreach( $variant->documentMandantMandants as $mandant){
                        
                        if( self::universalDocumentPermission($document,false,false, true) == true){
                           if(self::userHasMandantVariant( $document ) == 0 ){
                            $hasPermission = true;
                            $variant->hasPermission = true;
                            $object->permissionExists = true;
                           }
                           else{
                                if($variant->id == self::userHasMandantVariant( $document ) ){
                                    $hasPermission = true;
                                    $variant->hasPermission = true;
                                    $object->permissionExists = true;
                                }
                            }
                           
                            // dd('test');
                        }
                        else if( in_array($mandant->mandant_id,$mandantIdArr) ){
                            // dd($variant->documentMandantMandants);
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
        
        $object->variants = $variants;
        return $object;
    }//end documentVariant permission
    
    
    /**
     * Document variant permission
     * @param collection $document
     * @return object $object 
     */
    static function documentUsersPermission($document){
        
        /*  class $object stores 2 attributes: 
            1. permissionExists( this is a global hasPermissionso we dont have to iterate again to see if permission exists  )
            2. variants (to store variants)[duuh]
        */
        
        $object = new \StdClass(); 
        $object->permissionExists = false;
        $mandantId = MandantUser::where('user_id',Auth::user()->id)->pluck('id');
        $mandantUserMandant = MandantUser::where('user_id',Auth::user()->id)->pluck('mandant_id');
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
                        if( $this->universalDocumentPermission($document,false) == true){
                            $hasPermission = true;
                            $variant->hasPermission = true;
                            $object->permissionExists = true;
                        }
                        else if( in_array($mandant->mandant_id,$mandantIdArr) ){
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
        
        $object->variants = $variants;
        return $object;
    }//end documentVariant permission
    
    
    
    /**
     * Check if document has pdf (used for example show title etc)
     * @param collection $document
     * @return bool
     */
    static function hasPdf( $document ){
        $hasPdf = false;
        foreach($document->documentUploads as $k => $attachment){
            if($hasPdf == false){
                $type = \File::extension(url('open/'.$document->id.'/'.$attachment->file_path) );  
                if( $type == 'pdf')
                    $hasPdf = true;
                }
            }
        return $hasPdf;        
    }
    
    /**
     * Get type of file
     * @param collection $document
     * @param collection $attachment
     * @return string || null
     */
    static function htmlObjectType( $document,$attachment ){
        $type = \File::extension(url('open/'.$document->id.'/'.$attachment->file_path) );
        $htmlObjectType = null;    
        if( $type == 'png' || $type == 'jpg' || $type == 'jpeg' || $type == 'gif')
            $htmlObjectType = 'image/'.$type;
        elseif( $type == 'pdf')
            $htmlObjectType = 'application/pdf';
        return $htmlObjectType;        
    }
    
    /**
     * Get Mandants parent Mandant if he has one
     * @param Mandant $mandant
     * @return Mandant | bool
     */
    static function getHauptstelle( $mandant ){
        $hauptstelleId = $mandant->mandant_id_hauptstelle;
        if($hauptstelleId){
            return Mandant::find($hauptstelleId);
        } else return false;
    }
    
    /**
     * Get User by ID
     * @param int $id
     * @return User | bool
     */
    static function getUser( $id ){
        if($user = User::find($id)){
            return $user;
        } else return false;
    }
    
    /**
     * Get Mandant by User ID
     * @param int $id
     * @return Mandant | bool
     */
    static function getMandant( $id ){
        if($mandantUser = MandantUser::where('user_id', $id)->first()){
            return Mandant::find($mandantUser->mandant_id);
        } else return false;
    }
    
    
    /**
     * Get all user by passed role ID
     * @param int $roleId
     * @param int $userId
     * @return string $options
     */
    static function getUsersByInternalRole($roleId, $userId){
        // dd($roleId.' '.$userId);
        $mandantUsersNeptun = array();
        $mandantUsers = MandantUser::all();
        
        // Get all users with telefonliste roles where mandant is with neptun flag
        foreach ($mandantUsers as $mandantUser) {
            foreach($mandantUser->role as $role){
                if($role->phone_role && $mandantUser->mandant->rights_admin && $role->id == $roleId){
                    if(!in_array($mandantUser, $mandantUsersNeptun))
                        array_push($mandantUsersNeptun, $mandantUser);
                }
            }
        }
        
        $html = '';
        
        if($mandantUsersNeptun){
            foreach($mandantUsersNeptun as $mandantUser){
                ($userId == $mandantUser->user->id) ? $selected = "selected" : $selected = "";
                $html .= '<option value="'. $mandantUser->user->id .'" data-mandant="'. $mandantUser->mandant->id .'" ' . $selected .'>';
                $html .= $mandantUser->user->first_name .' '. $mandantUser->user->last_name;
                $html .= ' ['. $mandantUser->mandant->mandant_number .' - '. $mandantUser->mandant->kurzname .']';
                $html .= '</option>';
            }
        }
        
        return $html;
        // dd($html);
    }
    
    /**
     * Get Mandant Roles from MandantUserRole and Mandant
     * @param MandantUserRole $object
     * @param Mandant $object
     * @return Mandant | bool
     */
    static function getMandantRoles( $mandantUserRole, $mandant ){
       //
    }
    
    /**
     * Check if Mandant has Wiki permission
     * @return bool
     */
    static function getMandantWikiPermission(){
        $user = Auth::user();
        $mandantUser = MandantUser::where('user_id', $user->id)->first();
        if(isset($mandantUser->mandant))
            return (bool)$mandantUser->mandant->rights_wiki;
        else return false;
    }
    
    /**
     * Is Freigeber for the seleted document function
     * @param collection $document
     * @return bool
     */
    static function isThisDocumentFreigeber($document){
        $uid = Auth::user()->id;
        $approval = DocumentApproval::where('document_id',$document->id)->where('user_id',$uid)->get();
        if( count($approval)  )
            return true;
        return false;
        
    }
    
    /**
     * Check if user has any visible roles, return true if so, else return false
     * @return bool
     */
    static function phonelistVisibility($user, $mandant){
        $rolesCount = 0;
        foreach($user->mandantRoles as $mandantUserRole) {
            if(ViewHelper::getMandant(Auth::user()->id)->rights_admin || ViewHelper::universalHasPermission()){
                if( $mandantUserRole->role->phone_role || $mandantUserRole->role->mandant_role ){
                    if( !in_array($mandantUserRole->role->id, array_pluck($mandant->usersInternal,'role_id')) )
                        $rolesCount += 1; 
                }
            } else {
                if($mandant->rights_admin){
                    if( $mandantUserRole->role->phone_role ){
                        if( !in_array($mandantUserRole->role->id, array_pluck($mandant->usersInternal,'role_id')) )
                            $rolesCount += 1; 
                    }
                } else {
                    if( $mandantUserRole->role->mandant_role ){
                        if( !in_array($mandantUserRole->role->id, array_pluck($mandant->usersInternal,'role_id')) )
                            $rolesCount += 1; 
                    }
                }
            }
        }
        
        return $rolesCount;
    }
    
}