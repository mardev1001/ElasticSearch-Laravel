<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JuristCategoryMeta extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['name']; //whitelist
    
    public function metaInfos(){
        return $this->hasMany(JuristCategoryMetaField::class,'jurist_category_meta_id','id');
    }
}
