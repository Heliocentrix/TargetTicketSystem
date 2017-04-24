<?php

namespace TargetInk;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'second_email',
        'company',
        'company_slug',
        'phone',
        'web',
        'type',
        'start_date'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    public function scopeCompanies($query)
    {
        return $query->where('admin', '=', 0);
    }

    public function scopeAdmin($query)
    {
        return $query->where('admin', '=', 1);
    }

    public function files()
    {
        return $this->hasMany('TargetInk\File', 'client_id');
    }

    public function services()
    {
        return $this->hasMany('TargetInk\Service', 'client_id');
    }

    public function tickets()
    {
        return $this->hasMany('TargetInk\Ticket', 'client_id')->orderBy('order', 'desc');
    }

    public function openTickets(){
        return $this->hasMany('TargetInk\Ticket', 'client_id')->where('archived', 0)->orderBy('order', 'desc'); 
    }

    public function seoFiles()
    {
        return $this->files()->seo()->orderBy('created_at', 'desc');
    }

    public function infoFiles()
    {
        return $this->files()->information()->orderBy('created_at', 'desc');
    }

    public function adverts()
    {
        return $this->hasMany('TargetInk\Advert', 'client_id');
    }
}
