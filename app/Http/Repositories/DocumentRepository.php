<?php
namespace App\Http\Repositories;
/**
 * Created by PhpStorm.
 * User: Marijan
 * Date: 04.05.2016.
 * Time: 11:42
 */

use DB;

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
     
}
