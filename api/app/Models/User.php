<?php

namespace Incident\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Incident\Http\Requests;


class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    //protected $table = "users";
    protected $fillable = [
        'email', 'nombre', 'password', 'isActive'
    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'isActive',
    ];

    /**
     *
     */
    protected $casts = [
        'isActive' => 'boolean',
    ];

    public static function getUpdatable()
    {
        return [
            'nombre', 'isActive','email'
        ];
    }

}
