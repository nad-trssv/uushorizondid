<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSeo extends Model
{
    protected $fillable = ['event_id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function translations()
    {
        return $this->hasMany(EventSeoTranslation::class);
    }

    public function translation(string $langCode = 'en')
    {
        return $this->translations()
                    ->whereHas('language', fn($q) => $q->where('code', $langCode))
                    ->first();
    }
}