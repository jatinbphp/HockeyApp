<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Child extends Authenticatable implements JWTSubject
{
    use HasFactory,SoftDeletes,Notifiable;

    protected $fillable = [
        'id',
        'parent_id',
        'firstname',
        'lastname',
        'username',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'province_id',
        'school_id',
        'looking_sponsor',
        'status',
        'session_token',
        'device_type',
        'device_id',
        'image',
        'gender',
        'age_group',
        'remember_token'
    ];


     /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
    */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function parent()
    {
        return $this->belongsTo(Parent::class, 'parent_id');
    }

    public function getFullNameAttribute(){
       
        return $this->firstname.' '.$this->lastname;
       
    }

    public function getParentFullNameAttribute(){
       
        // Check if the parent exists before trying to access properties
        if ($this->parent) {
            return "{$this->parent->firstname} {$this->parent->lastname}";
        }
        return 'N/A'; // Default value if no parent is associated
       
    }
}
