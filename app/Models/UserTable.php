<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\FlagScope;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;


class UserTable extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'user_table';
    protected $fillable = ['id', 'name', 'email', 'phone', 'password', 'genre'];
    protected $timestamp =  false;


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key-value array of custom claims.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


// this is relation in staff relative 
    public function staff()
    {
        return  $this->hasMany(staff::class, 'staff_id');
    }
// this is relation 
    public function doctor()
    {
        return  $this->hasOne(Doctor::class, 'doctor_id');
    }



    protected static function boot()
    {
        parent::boot();

        return static::addGlobalScope(new FlagScope);
    }

    
}
