<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiCategory extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = [ 'name', 'top_category' ]; //whitelist
}
