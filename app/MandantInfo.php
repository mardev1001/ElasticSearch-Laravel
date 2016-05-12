<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MandantInfo extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = 
        [
            'mandant_id','prokura','betriebsnummmer','handelsregister',
            'handelsregister_sitz','steuernummer_lohn','ust_ident_number','zausatzinfo_steuer',
            'berufsgenossenschaft_number','berufsgenossenschaft_zusatzinfo','erlaubniss_gultig_ab','erlaubniss_gultig_von',
            'geschaftsjahr','geschaftsjahr_info','bankverbindungen','info_wichtiges','info_sonstiges',
            'steuernummer_lohn','mitarbeiter_finanz_id','mitarbeiter_edv_id','mitarbeiter_vertrieb_id','mitarbeiter_umwelt_id',
        ]; //whitelist
        
    public function mandant(){
        return $this->belongsTo('App\Mandant');
    }
}
 