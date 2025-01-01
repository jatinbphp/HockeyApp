<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'child_id',
        'order_id',
        'amount',       
        'status',
        'transaction_id',
        'response',
        'payment_date',
    ];

    public function children()
    {
        return $this->belongsTo(Child::class, 'child_id', 'id');
    }
}
