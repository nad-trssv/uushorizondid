<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $fillable = [
        'event_id', 'first_name', 'last_name', 'email', 
        'phone', 'notes', 'status', 'participants_count'
    ];

    protected $casts = [
        'participants_count' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}