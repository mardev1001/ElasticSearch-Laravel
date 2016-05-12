<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['name','mandant_required','admin_role','system_role','mandant_role','wiki_role']; //whitelist

    public function hasRole(){
        
    }
    
    public function isManager(){
        
    }
}
