<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = 
    [
        'document_type_id', 'document_status_id', 'user_id','date_created','version',
        'name','name_long','owner_user_id','search_tags',
        'summary','date_published','date_modified','date_expired',
        'version_parent','document_group_id','iso_category_id',
        'show_name','adressat_id','betreff','document_replaced_id',
        'date_approved','email_approval','approval_all_roles',
        'approval_all_mandants','pdf_upload','is_attachment'
    ]; //whitelist
    
     
    public function getDatePublishedAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y');
    }
    
    // public function getCreatedAtDttribute($value)
    // {
    //     return Carbon::parse($value)->format('d.m.Y H:m:s');
    // }
    
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:m:s');
    }
    
    public function setDatePublishedAttribute($value)
    {
        // dd($value);
        if(empty($value))
            $this->attributes['date_published'] = Carbon::parse($value)->addDay();
        else
            $this->attributes['date_published'] = Carbon::parse($value);
        
    }
     
    public function getDateExpiredAttribute($value)
    {
        if(empty($value) || $value == null || $value == '')
            return null;
        else
            return Carbon::parse($value)->format('d.m.Y');
            
    }
    
    public function setDateExpiredAttribute($value)
    {
         if(empty($value) || $value == null || $value == '')
            $this->attributes['date_expired'] = null;
        else
            $this->attributes['date_expired'] = Carbon::parse($value);
    }
    
    public function getCreatedAtAttribute($value)
    {
        
      return  $this->attributes['created_at'] = Carbon::parse($value)->format('d.m.Y');
    }
    
    public function setAdressatIdAttribute($value){
        if($value == null)
            $this->attributes['adressat_id'] = null; 
        elseif($value == "null" || empty($value) )
            $this->attributes['adressat_id'] = null; 
        else
            $this->attributes['adressat_id'] = $value;
    }
    
    public function setIsoCategoryIdAttribute($value){
        if($value == null)
            $this->attributes['iso_category_id'] = null; 
        elseif($value == "null" || empty($value) )
             $this->attributes['iso_category_id'] = null; 
        else
            $this->attributes['iso_category_id'] = $value;
    }
    
    public function documentType(){
        return $this->belongsTo('App\DocumentType');
    }
    
    public function documentStatus(){
        return $this->belongsTo('App\DocumentStatus');
    }
    
    public function documentAdressats(){
        return $this->belongsTo('App\Adressat','adressat_id');
    }
    
    public function published(){
        return $this->hasOne('App\PublishedDocument','document_id','id');
    }
    
    public function isoCategories(){
        return $this->belongsTo('App\IsoCategory', 'iso_category_id', 'id');
    }
    public function isoCategoriesWhereSlug($slug){
        return $this->belongsToMany('App\IsoCategory', 'iso_category_id', 'id')->where('slug',$slug);
    }
    
    public function editorVariant(){
            return $this->hasMany('App\EditorVariant');
    }
    public function editorVariantNoDeleted(){
            return $this->hasMany('App\EditorVariant')->where('deleted_at',null);
    }
    public function editorVariantOrderBy(){
            return $this->hasMany('App\EditorVariant')->orderBy('variant_number');
    }
    
    public function editorVariantDocument(){
            return $this->hasManyThrough('App\EditorVariantDocument','App\EditorVariant');
    }
    public function lastEditorVariant(){
            return $this->hasMany('App\EditorVariant')->orderBy('variant_number','desc')->take(1);
    }
    
    public function documentApprovals(){
        return $this->hasMany('App\DocumentApproval');
    }
    public function documentApprovalsApprovedDateNotNull(){
        return $this->hasMany('App\DocumentApproval')->whereNotNull('date_approved');
    }
    
    public function documentMandants(){
        return $this->hasManyThrough('App\DocumentMandant','App\EditorVariant') ;
    }
    
    
    public function documentMandantMandants(){
        return $this->hasManyThrough('App\DocumentMandantMandant','App\DocumentMandant','App\EditorVariant') ;
    }
    
    public function documentCoauthors(){
        return $this->hasManyThrough('App\DocumentCoauthor','App\User') ;
    }
    
    public function documentUploads(){
        return $this->hasManyThrough('App\DocumentUpload','App\EditorVariant') ;
    }
    
    public function publishedDocuments(){
        return $this->hasMany('App\PublishedDocument','document_id','id');
    }
}
