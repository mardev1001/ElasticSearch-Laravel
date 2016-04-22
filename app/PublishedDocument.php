<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublishedDocument extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['document_id','document_group_id','url_unique']; //whitelist
}
