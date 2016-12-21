<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'send_copy'];
    protected $dates = ['created_at'];
    
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function files(){
        return $this->hasMany('App\MessageFile', 'contact_message_id');
    }
}