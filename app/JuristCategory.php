<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JuristCategory extends Model
{
    protected $guarded = []; //blacklist
    protected $fillable = ['jurist_category_parent_id','name', 'slug', 'active', 'parent']; //whitelist
    
    public function isJuristCategoryParent(){
        return $this->hasMany('App\JuristCategory','jurist_category_parent_id','id');
    }
    
    public function juristenParent(){
        return $this->belongsTo('App\JuristCategory','id','jurist_category_parent_id');
    }
    
    public function juristCategories(){
        return $this->hasMany('App\JuristCategory','jurist_category_parent_id','id');
    }
    public function juristCategoriesActive(){
        return $this->hasMany('App\JuristCategory','jurist_category_parent_id','id')->where('active',1);
    }
    
    public function hasAllDocuments(){
        return $this->hasMany('App\Document','jurist_category_id','id');
    }
  
}
