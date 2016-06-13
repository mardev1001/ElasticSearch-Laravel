<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'username_sso', 'email', 'password', 'title', 'first_name', 'last_name', 'short_name', 'email_reciever', 'picture', 'active', 'active_from', 'active_to', 'birthday', 'created_by', 'phone', 'phone_short',];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    /**
     * Get the date format
     *
     * @param  string  $value
     * @return string
     */
    public function getBirthdayAttribute($value)    {
        
        if (empty($value)) return null;
        else return Carbon::parse($value)->format('d.m.Y');
    }
    
    
    /**
     * Get the date format
     *
     * @param  string  $value
     * @return string
     */
    public function getActiveFromAttribute($value)
    {
        if (empty($value)) return null;
        else return Carbon::parse($value)->format('d.m.Y');
    }
    
    
    /**
     * Get the date format
     *
     * @param  string  $value
     * @return string
     */
    public function getActiveToAttribute($value)
    {
        if (empty($value)) return null;
        else return Carbon::parse($value)->format('d.m.Y');
    }
    
    /**
     * Set the date format
     *
     * @param  string  $value
     * @return string
     */
    public function setBirthdayAttribute($value)
    {
        if (empty($value)) $this->attributes['birthday'] = null;
        else $this->attributes['birthday'] = Carbon::parse($value);
    }
     
    /**
     * Set the date format
     *
     * @param  string  $value
     * @return string
     */
    public function setActiveFromAttribute($value)
    {
        if (empty($value)) $this->attributes['active_from'] = null;
        else $this->attributes['active_from'] = Carbon::parse($value);
    }
    
    /**
     * Set the date format
     *
     * @param  string  $value
     * @return string
     */
    public function setActiveToAttribute($value)
    {
        if (empty($value)) $this->attributes['active_to'] = null;
        else $this->attributes['active_to'] = Carbon::parse($value);
    }
    
    /**
     * Set the password hash
     *
     * @param  string  $value
     * @return string
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    
    
    public function countMandants(){
       return $this->hasMany('App\MandantUser','user_id','id');
    }
    
    public function mandantUsers(){
       return $this->hasMany('App\MandantUser','user_id','id');
    }
    
    public function mandantRoles(){
       return $this->hasManyThrough('App\MandantUserRole','App\MandantUser');
    }
   
    public function is($roleName)
    {
        foreach ($this->roles()->get() as $role)
        {
            if ($role->name == $roleName)
            {
                return true;
            }
        }

        return false;
    }
}
