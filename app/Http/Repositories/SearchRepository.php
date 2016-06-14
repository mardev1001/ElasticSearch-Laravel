<?php
namespace App\Http\Repositories;
/**
 * User: Marijan
 * Date: 14.06.2016.
 * Time: 08:11
 */

use DB;
use App\Mandant;
use App\User;

class SearchRepository
{
    /**
     * Merge two collections
     *
     * @return object array $array
     */
     public function phonelistSearch($request ){
        
        if($request->has('deletedMandants') )
             $query = Mandant::withTrashed();
        else
            $query = Mandant::where('mandants.id','>',0);
        
         if( $request->has('parameter') )
            $query->where('name',$request->get('parameter'));
            
        $mandants = $query->get();   
            
        
        foreach($mandants as $mandant){
            
        }
        
        $query->join('mandant_users','mandant_users.mandant_id','=','mandants.id');
        $query->join('users','mandant_users.user_id','=','users.id');
        
        
        
        if( $request->has('parameter') ){
            // $query->where('name',$request->get('parameter'));
            
            $query->orWhere('first_name',$request->get('parameter'));
            $query->orWhere('last_name',$request->get('parameter'));
            
        }
         if( !$request->has('deletedUsers') )
           $query->whereNull('users.deleted_at');
            
       
        //   DB::enableQueryLog();
        //     $query->get();
        //   dd(DB::getQueryLog());
          
          dd($query->get());
          
          
        return $query->get();
     }
     
}
