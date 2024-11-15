<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CmsPages extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['id','page_name','page_content','created_at','updated_at'];
}
 