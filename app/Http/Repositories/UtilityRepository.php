<?php
namespace App\Http\Repositories;
/**
 * Created by PhpStorm.
 * User: Marijan
 * Date: 25.04.2016.
 * Time: 02:42
 */

use Auth;
use DB;
use App\MandantUser;
use App\MandantUserRole;


class UtilityRepository
{
    /**
     * Universal user has permissions check
     * @param array $userArray
     * @return bool 
     */
    public function universalHasPermission( $userArray=array(), $withAdmin=true, $debug=false){
        $uid = Auth::user()->id;
      
        $mandantUsers =  MandantUser::where('user_id',$uid)->get();
        $hasPermission = false;   
        foreach($mandantUsers as $mu){
            $userMandatRoles = MandantUserRole::where('mandant_user_id',$mu->id)->get();
            foreach($userMandatRoles as $umr){
               
               
                if($withAdmin == true){
                    if( $umr->role_id == 1 || in_array($umr->role_id, $userArray) )
                        $hasPermission = true;
                    
                }
                else{
                    if( in_array($umr->role_id, $userArray) == true ){
                        
                        $hasPermission = true;
                    }
                    
                }
                   
            }
        }
        
        return $hasPermission;
    }
    
    /**
     * Check if Mandant has Wiki permission
     * @return bool
     */
    static function getMandantWikiPermission(){
        $user = Auth::user();
        $mandant = MandantUser::where('user_id', $user->id)->first()->mandant;
        return (bool)$mandant->rights_wiki;
    }
     
}
