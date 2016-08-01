<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WikiPage extends Model
{
    
    protected $guarded = []; //blacklist
    protected $fillable = 
    [
        'user_id', 'status_id', 'category_id', 'name', 'subject',  'content', 'allow_all'
    ]; //whitelist
     
    public function getDateExpiredAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y');
    }
    
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:m:s');
    }
    
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y H:m:s');
    }
    
    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
