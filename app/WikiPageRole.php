<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiPageRole extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = [ 'wiki_page_id', 'role_id' ]; //whitelist
    
}
