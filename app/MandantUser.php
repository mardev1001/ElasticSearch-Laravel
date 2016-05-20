<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MandantUser extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['mandant_id','user_id']; //whitelist
    
    public function user(){
        return $this->hasOne('App\User','id','user_id');
    }
    
    public function mandant(){
        return $this->hasOne('App\Mandant','id','mandant_id');
    }
    
    public function mandantUserRoles(){
        return $this->hasMany('App\MandantUserRole','mandant_user_id','id');
    }
}
