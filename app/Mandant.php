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
        'adresszusatz','strasse','plz','hausnummer','ort','telefon',
        'kurzwahl','fax','email','website','geschaftsfuhrer_history'
    ]; //whitelist
    
    public function mandantInfo(){
        return $this->hasOne('App\MandantInfo');
    }
}
