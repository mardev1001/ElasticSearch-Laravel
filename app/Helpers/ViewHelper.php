<?php
namespace App\Helpers;
use Request;
class ViewHelper
{
    /**
     * Set Input value 
     *
     * @param string $value
     * @param string old || null
     * @return string $value || $old
     */
   /* static function setInput($value='',$old=null){
        if( Request::is('/edit') && $old != null )
            echo $old;
        elseif( Request::is('/edit') && $old == null )    
            echo $value;
        echo $value;
    }*/
    
    /**
     * Set select value 
     *
     * @param object array $collections
     * @param string $value
     * @return string $string
     */
    static function setSelect($collections=array(),$value='',$old=null){
        if( $old != null)
            $value = $old;
       $string = '';
       
       $string .= '<option></option>';
       foreach($collections as $collection){
           $string .= '<option value="'.$collection->id.'" '; 
            if( !empty( $value) && $collection->id == $value)
                echo 'selected';
           $string .= '>'.$collection->name;
           $string .= '</option>';
       }
       echo $string;
    }
    
    /**
     * Echo required asterisk 
     *
     * @echo string 
     */
    static function asterisk(){
        echo '<i class="fa fa-asterisk text-info"></i>';
    }
}

