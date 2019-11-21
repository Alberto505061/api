<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 29/12/2018
 * Time: 20:09
 */

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable;

    protected $fillable = [
      'firstName','lastName', 'email','password','status'
    ];

    protected $hidden = [
        'password'
    ];

    public function board(){
        return $this->hasMany(Sensor::class, 'id_user');
    }

    public function findForPassport($identifier)
    {
        return $this->where('email', $identifier)->first();
    }
}