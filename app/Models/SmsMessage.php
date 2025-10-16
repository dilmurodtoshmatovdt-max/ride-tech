<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmsMessage extends BaseModel
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'code',
        'phone',
        'ip',
        'created',
        'response',
        'user_id',
        'order_id',
        'message',
        'type',
        'used_at',
        'attempt_count',
        'attempt_available_at',
        'attempt_last_at',
        'uuid'
    ];
}
