<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['name','document_art','document_role','read_required','allow_comments','order_number', 'menu_position']; //whitelist

    public function documents(){
        return $this->hasMany('App\Document');
    }
    
}
