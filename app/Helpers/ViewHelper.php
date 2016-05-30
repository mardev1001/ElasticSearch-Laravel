<?php
namespace App\Helpers;
use Request;
class ViewHelper
{
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
    static function setCheckbox( $inputName ,$data , $old, $label='', $required=false, $classes=array(), $dataTags=array() ){
        $string = '';
        $string = view('partials.inputCheckbox',
                compact('inputName','classes', 'dataTags','data','label','old','required')
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
    static function setArea( $inputName ,$data , $old, $label='', $placeholder='', $required=false, $classes=array(), $dataTag=array()  ){
        $string = '';
        if( $placeholder == '')
            $placeholder = $label;
         
            $string = view('partials.inputTextarea',
                    compact('inputName','data','label','old','placeholder','required')
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
                //  dd($collection->$relationship);
                foreach($collection->$relationship as $cr){
                    // var_dump($cr->$key);
                     if( $cr->$key == $value )
                        echo 'selected '.$cr->$key;
                     else
                        echo 'not selected '.$cr->$key;
                }
            }
        }
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
}

