<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'event_id', 'language_id', 'title', 'short_description', 
        'full_description', 'location', 'requirements', 'included'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}