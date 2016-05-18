<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MandantUserRole extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['mandant_user_id','role_id']; //whitelist
    
    public function mandantUsers(){
        $this->hasMany('App\MandantUser','mandant_user_id','id');
    }
}
