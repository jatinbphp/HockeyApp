<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['name','town','province_id','status'];

    public function children()
    {
        return $this->hasMany(Child::class, 'school_id');
    }
}
 