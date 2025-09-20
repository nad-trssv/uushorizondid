<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['slug', 'user_id', 'published_at', 'status', 'image'];

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

}

