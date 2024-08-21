<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['id','user_id','user_type','device_id','device_type','message','status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class, 'user_id'); 
    }

    public function child()
    {
        return $this->belongsTo(Child::class, 'user_id'); 
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
 