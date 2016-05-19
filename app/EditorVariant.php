<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EditorVariant extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['document_id','document_status_id','inhalt']; //whitelist

    public function documentUpload(){
        return $this->hasMany('App\DocumentUpload');
    }
}
