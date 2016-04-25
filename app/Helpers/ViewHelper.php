<?php
namespace App\Helpers;
use Request;
class ViewHelper
{
    /**
     * Set select value 
     *
     * @param object array $collections
     * @param string $value
     * @return string $string
     */
    static function setSelect($collections=array(),$value=''){
        if( Request::is('*/create'))
            return 'kriejt';
        $string = '';
       $string .= '<option></option>';
       foreach($collections as $collection){
           $string .= '<option value="'.$collection->id.'" '; 
            if( !empty( $value) && $collection->id == $value)
                echo 'selected';
           $string .= '>';
           $string .= '</option>';
       }
       return $string;
    }
}

