<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $guarded  = array('id');
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function getBirthdayAttribute($value)
    {
        $time = strtotime($value);
        return date("m/d/Y", $time);
    }

    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = date("Y-m-d", strtotime($value) );
    }

    public function registrations()
    {
        return $this->hasMany('App\UsersCoursesRegistration');
    }
}
