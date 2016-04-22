<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentMandant extends Model
{
    use SoftDeletes;
    protected $guarded = []; //blacklist
    protected $fillable = ['document_id','editor_variant_id','mandant_id','role_id']; //whitelist
}
