<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name','status'];

    public function children()
    {
        return $this->hasMany(Child::class, 'school_id');
    }

    public function school()
    {
        return $this->hasMany(School::class,'province_id');
    }
}
