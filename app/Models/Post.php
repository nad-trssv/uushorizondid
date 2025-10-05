<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['slug', 'user_id', 'published_at', 'status', 'image', 'views'];
    protected $casts = [
        'published_at' => 'datetime',
    ];
    
    public function translations()
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function translation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        return $this->translations->where('language_id', $locale)->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function seo()
    {
        return $this->hasOne(PostSeo::class);
    }
    
    public function gallery()
    {
        return $this->morphMany(Gallery::class, 'galleryable');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentsCount()
    {
        return $this->comments()->count();
    }
    public function averageRating()
    {
        return $this->comments()->avg('rating');
    }

    public function getTitleAttribute()
    {
        $lang = app()->getLocale();
        $defaultLang = Language::where('is_default', true)->value('code');
        return optional($this->translations->firstWhere('language.code', $lang))->title ?? optional($this->translations->firstWhere('language.code', $defaultLang))->title;
    }
    
    public function getDescriptionAttribute()
    {
        $lang = app()->getLocale();
        $defaultLang = Language::where('is_default', true)->value('code');
        return optional($this->translations->firstWhere('language.code', $lang))->description ?? optional($this->translations->firstWhere('language.code', $defaultLang))->description;
    }

    public function getShortDescriptionAttribute()
    {
        $lang = app()->getLocale();
        $defaultLang = Language::where('is_default', true)->value('code');
        return optional($this->translations->firstWhere('language.code', $lang))->short_description ?? optional($this->translations->firstWhere('language.code', $defaultLang))->short_description;
    }

    public function comments_count()
    {
        return $this->comments()->count();
    }

}

