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
        return $this->hasManyThrough('App\User', 'App\MandantUser','mandant_id','id');
    }
    
    public function internalUsers(){
        return $this->hasMany('App\InternalMandantUser', 'mandant_id', 'id');
    }
    
}
