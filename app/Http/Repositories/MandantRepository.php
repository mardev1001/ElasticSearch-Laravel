<?php
namespace App\Http\Repositories;
/**
 * User: Marijan
 * Date: 10.05.2016.
 * Time: 09:51
 */

use DB;

class MandantRepository
{
    /**
     * Merge two collections
     *
     * @return object array $array
     */
     public function generateDummyData($collectionOne, $collectionTwo ){
        $collectionOne->merger($collectionOne);
        return $collectionOne;
     }
     
}
