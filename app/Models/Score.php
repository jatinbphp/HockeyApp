<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Score extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['skill_id','student_id','province_id','score','time_duration','status','video','submited_score','submited_time'];

    const STATUS_TYPE_PENDING  = 'pending';
    const STATUS_TYPE_ACCEPT   = 'accept';
    const STATUS_TYPE_REJECT = 'reject';
    
    public static $allStatus = [
        self::STATUS_TYPE_PENDING => 'Pending',
        self::STATUS_TYPE_ACCEPT => 'Accept',
        self::STATUS_TYPE_REJECT => 'Reject',    
    ];

    public function skills()
    {
        return $this->belongsTo(Skill::class, 'skill_id');
    }

    public function child()
    {
        return $this->belongsTo(Child::class, 'student_id');
    }
}
