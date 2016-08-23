<?php
namespace App\Helpers;
use Request;

use Auth;

use App\Mandant;
use App\MandantUser;
use App\MandantUserRole;
class ViewHelper
{
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
    static function setComplexMultipleSelect($collection,$relationship, $value, $key='id',$oneLessForeach=false){
        if($oneLessForeach == false){
            if( count($collection) )
            foreach($collection as $col){
                foreach($col->$relationship as $userValue){
                    if( $userValue->$key == $value )
                       echo 'selected ';
                }  
            }
            
        }
        else{
            if( count($collection->$relationship) > 0 ){
                foreach($collection->$relationship as $cr){
                     if( $cr->$key == $value )
                        echo 'selected ';
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
        $newstring = '';
        $haystack = html_entity_decode(strip_tags($haystack));
        $extractLenght = 128;
        $needlePosition = strpos($haystack , $needle);
        $newstring = '... ' . substr($haystack, $needlePosition, 128) . ' ...';
        return $newstring;    
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
    static function generateCommentBoxes($collection,$title){
        $string = '';
        $string = view('partials.comments', compact('collection','title') )->render();
        echo $string;
    }
    
    /**
     * Universal user has permissions check
     * @param array $userArray
     * @return bool 
     */
    static function universalHasPermission( $userArray=array() ){
        $uid = Auth::user()->id;
        $mandantUsers =  MandantUser::where('user_id',$uid)->get();
        foreach($mandantUsers as $mu){
            $userMandatRoles = MandantUserRole::where('mandant_user_id',$mu->id)->get();
            foreach($userMandatRoles as $umr){
                if( $umr->role_id == 1 || in_array($umr->role_id, $userArray))//wiki redaktur
                    return true;
            }
        }
        return false;
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
    
}

