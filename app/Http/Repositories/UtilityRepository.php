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
    public function universalHasPermission( $userArray=array() ){
        $uid = Auth::user()->id;
        $mandantUsers =  MandantUser::where('user_id',$uid)->get();
        foreach($mandantUsers as $mu){
            $userMandatRoles = MandantUserRole::where('mandant_user_id',$mu->id)->get();
            foreach($userMandatRoles as $umr){
                if( $umr->role_id == 1 || in_array($umr->role_id, $userArray))//wiki redaktur
                    return true;
            }
        }
        return false;
    }
     
}
