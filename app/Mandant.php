<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mandant extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = 
    [
        'name','kurzname','mandant_number','rights_wiki',
        'rights_admin','logo','mandant_id_hauptstelle','hauptstelle',
        'adresszusatz','strasse','plz','hausnummer','ort','bundesland','telefon',
        'kurzwahl','fax','email','website','geschaftsfuhrer_history','active'
    ]; //whitelist
    
    public function mandantInfo(){
        return $this->hasOne('App\MandantInfo');
    }
    
    public function mandantUsers(){
        
        return $this->hasMany('App\MandantUser', 'mandant_id', 'id')->groupBy('user_id');
    }
    
    public function users(){
        // return $this->hasManyThrough('App\User', 'App\MandantUser','mandant_id','id');
        return $this->belongsToMany('App\User', 'mandant_users', 'mandant_id', 'user_id')->where('mandant_users.deleted_at', null);
    }
    
    public function usersActive(){
        return $this->belongsToMany('App\User', 'mandant_users', 'mandant_id', 'user_id')->where('mandant_users.deleted_at', null)->where('active', true);
    }
    
    public function usersInactive(){
        return $this->belongsToMany('App\User', 'mandant_users', 'mandant_id', 'user_id')->where('mandant_users.deleted_at', null)->where('active', false);
    }
    
    public function internalUsers(){
        return $this->hasMany('App\InternalMandantUser', 'mandant_id', 'id');
    }
    
}
