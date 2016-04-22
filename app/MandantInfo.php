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
            'heschaftsjahr','heschaftsjahr_info','bankverbindungen','info_wichtiges','info_sonstiges',
            'mitarbeiter_lohn_id','mitarbeiter_finanz_id','mitarbeiter_edv_id','mitarbeiter_vertrieb_id','mitarbeiter_umwelt_id',
        ]; //whitelist
}
 