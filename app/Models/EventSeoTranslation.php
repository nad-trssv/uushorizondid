<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSeoTranslation extends Model
{
    protected $fillable = [
        'event_seo_id',
        'language_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public function seo()
    {
        return $this->belongsTo(EventSeo::class, 'event_seo_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}