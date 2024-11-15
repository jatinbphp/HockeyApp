<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category_id',
        'short_description',
        'long_description',
        'instruction',
        'score_instruction',
        'video_url',
        'featured_image',
        'icon_image',
        'position',
        'status'
    ];

    public function score()
    {
        return $this->hasMany(Score::class,'skill_id');
    }
}
 