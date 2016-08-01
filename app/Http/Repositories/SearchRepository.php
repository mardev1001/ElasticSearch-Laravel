<?php
namespace App\Http\Repositories;
/**
 * User: Marijan
 * Date: 14.06.2016.
 * Time: 08:11
 */

use DB;
use App\Mandant;
use App\MandantUser;
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
            $query->where('name','LIKE', '%'.$request->get('parameter').'%');
            
        $mandants = $query->get();   
            // dd($mandants);
            // dd($request->all());
        if( count($mandants) ){
            
            foreach($mandants as $mandant){
                $mandantQuery = $mandant->users();
                
               /* if( $request->has('parameter') )
                    $mandantQuery->where('first_name',$request->get('parameter') )->orWhere('last_name',$request->get('parameter') );*/
               
                if($request->has('deletedMandants') )
                    $mandantQuery->withTrashed();
                
                $mandant->usersInMandants = $mandantQuery->get();
            }
        }
       else{
            $query = User::where('users.id','>',0);
                
            if( $request->has('parameter') )
                $query->where('first_name',$request->get('parameter') )->orWhere('first_name',$request->get('parameter') );
            
            if($request->has('deletedUsers') )
                $query ->withTrashed();        
             
            // $additionalQuery = $query;   
            $users = $query->get();   
            if( count($users) ){
                $usersIds = $query->pluck('id');   
                $userMandants = MandantUser::whereIn('user_id',$usersIds)->pluck('mandant_id');
                $mandants = Mandant::whereIn('id',$userMandants)->get();
                
                foreach($mandants as $mandant){
                    $userQuery = User::whereIn('id',$usersIds);
                     if( $request->has('parameter') )
                        $userQuery->where('first_name',$request->get('parameter') )->orWhere('last_name',$request->get('parameter') );
                     if($request->has('deletedUsers') )
                        $userQuery ->withTrashed();    
                        
                    $mandant->usersInMandants = $userQuery->get();
                }
                
            }
       }
        return $mandants;
     }
     
}
