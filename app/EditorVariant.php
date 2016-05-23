<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EditorVariant extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = ['document_id','document_status_id','inhalt','approval_all_mandants']; //whitelist

    public function documentUpload(){
        return $this->hasMany('App\DocumentUpload');
    }

    public function documentMantant($document_id,$variant_id){
        return $this->hasMany('App\DocumentMandant')->whereIn($document_id,$variant_id);
    }
}
