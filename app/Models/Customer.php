<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable implements JWTSubject
{
    use  HasApiTokens, HasFactory, Notifiable;
    protected $guard = ["customer"];

    protected $table = 'customers';


    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'education'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     /** * Get the identifier that will be stored in the subject claim of the JWT.
     *  * * @return mixed */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /** * Return a key value array, containing any custom claims to be added to the JWT.
     * * * @return array */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function attachements(): MorphOne
    {
        return $this->morphOne(Attachement::class, 'attachmentable');
    }
}
