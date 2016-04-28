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
    static function setInput( $inputName ,$data , $old, $label='', $placeholder='', $required=false ){
        $string = '';
        if( $placeholder == '')
            $placeholder = $label;
         
            $string = view('partials.inputText',
                    compact('inputName','data','label','old','placeholder','required')
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
    static function setCheckbox( $inputName ,$data , $old, $label='', $required=false ){
        $string = '';
        
        $string = view('partials.inputCheckbox',
                compact('inputName','data','label','old','required')
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
     * @return string $value || $old
     */
    static function setArea( $inputName ,$data , $old, $label='', $placeholder='', $required=false ){
        $string = '';
        if( $placeholder == '')
            $placeholder = $label;
         
            $string = view('partials.inputTextarea',
                    compact('inputName','data','label','old','placeholder','required')
                )->render();
   
     
      echo $string;
    }
    
    
    /**
     * Set select value 
     *
     * @param object array $collections
     * @param string $value
     * @return string $string
     */
    static function setSelect($collections=array(), $inputName,$inputName ,$data , $old, $label='', $placeholder='', $required=false ){
        if( $old == '' &&  !isset( $data->$inputName) )
            $value = $data->$inputName;
            
       $string = '';
        $string = view('partials.inputSelect',
                    compact('collections','inputName','data','label','old','placeholder','required')
                )->render();
       
     
       echo $string;
    }
    
    /**
     * Echo required font awesome asterisk 
     *
     * @echo string 
     */
    static function asterisk(){
        echo '<i class="fa fa-asterisk text-info"></i>';
    }
}

