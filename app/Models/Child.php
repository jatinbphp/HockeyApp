<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Child extends Model
{
    use HasFactory,SoftDeletes;

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
        'status'
    ];
}
