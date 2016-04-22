<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MandantUser extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['mandant_id','user_id']; //whitelist
}
