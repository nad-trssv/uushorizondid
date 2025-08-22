<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkTimeException extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'is_full_day',
        'repeat_annually',
        'reason',
    ];

    protected $casts = [
        'is_full_day' => 'boolean',
        'repeat_annually' => 'boolean',
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];
}
