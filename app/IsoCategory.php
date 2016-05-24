<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IsoCategory extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['iso_category_parent_id','name', 'active', 'parent']; //whitelist
}
