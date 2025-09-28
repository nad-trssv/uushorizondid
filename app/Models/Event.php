<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'slug', 'published_at', 'status', 'image', 'views',
        'max_participants', 'current_participants', 'price', 
        'start_time', 'end_time', 'registration_deadline'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'registration_deadline' => 'datetime',
        'published_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    public function translations()
    {
        return $this->hasMany(EventTranslation::class);
    }

    public function translation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('language_id', $locale)->first();
    }

    public function seo()
    {
        return $this->hasOne(EventSeo::class);
    }
    
    public function gallery()
    {
        return $this->morphMany(Gallery::class, 'galleryable');
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function confirmedParticipants()
    {
        return $this->hasMany(EventParticipant::class)->where('status', 'confirmed');
    }

    public function participantsCount()
    {
        return $this->participants()->count();
    }

    public function confirmedParticipantsCount()
    {
        return $this->confirmedParticipants()->count();
    }

    public function hasAvailableSpots()
    {
        return $this->confirmedParticipantsCount() < $this->max_participants;
    }

    public function availableSpots()
    {
        return $this->max_participants - $this->confirmedParticipantsCount();
    }

    public function isRegistrationOpen()
    {
        return $this->registration_deadline 
            ? now()->lte($this->registration_deadline)
            : true;
    }
}