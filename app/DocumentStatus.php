<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentStatus extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['name']; //whitelist
}
