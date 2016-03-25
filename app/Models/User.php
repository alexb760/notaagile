<?php

namespace Incident\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;

    protected $table = "users";
    protected $fillable = [
        'email', 'nombre', 'password', 'usuario', 'isActive'
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


    /**
     * Validaciones para los campos de la tabla
     * @return array
     */
    public static function rules()
    {
        return [
            'nombre' => 'required|max:100',
            'usuario' => 'required|unique:users,usuario|max:200|min:6',
            'password' => 'required|min:8',
            'email' => 'required|email',
            'isActive' => 'required|boolean'
        ];
    }


}
