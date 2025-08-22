<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointments extends Model
{
    protected $fillable = [
        'clien_name',
        'client_lastname',
        'client_phone',
        'client_email',
        'service_id',
        'user_id',
        'price',
        'appointment_start',
        'appointment_end',
        'description'
    ];

    protected $casts = [
        'appointment_start' => 'datetime',
        'appointment_end' => 'datetime',
    ];
    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
