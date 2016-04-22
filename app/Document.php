<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;
    
    protected $guarded = []; //blacklist
    protected $fillable = 
    [
        'document_type_id','user_id','date_created','version',
        'name','owner_user_id','document_status_id','search_tags',
        'summary','date_published','date_modified','date_expired',
        'version_parent','document_group_id','iso_category_id','upload_filename',
        'show_name','adressat_id','betreff','document_replaced_id',
        'date_approved','email_approval','approval_all_roles','approval_all_mandants',
        'approval_all_mandants','pdf_upload'
    ]; //whitelist
}
