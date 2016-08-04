<?php
namespace App\Helpers;
use Request;
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
    
    
    
}

