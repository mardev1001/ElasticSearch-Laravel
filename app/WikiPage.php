<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WikiPage extends Model
{
    
    protected $guarded = []; //blacklist
    protected $fillable = 
    [
        'user_id', 'status_id', 'menu_name', 'menu_parent', 'menu_parent_id',
        'subject', 'headline', 'date_expired', 'content', 'allow_all'
    ]; //whitelist
     
    public function getDateExpiredAttribute($value)
    {
        return Carbon::parse($value)->format('d.m.Y');
    }
    
    public function setDateExpiredAttribute($value)
    {
        $this->attributes['date_expired'] = Carbon::parse($value);
    }
}
