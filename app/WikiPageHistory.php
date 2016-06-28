<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiPageHistory extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable =  'wiki_page_id', 'user_id' ]; //whitelist
  
}
