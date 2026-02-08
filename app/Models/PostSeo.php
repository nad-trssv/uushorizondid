<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostSeo extends Model
{
    protected $fillable = ['post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function translations()
    {
        return $this->hasMany(PostSeoTranslation::class);
    }

    public function translation(string $langCode = 'en')
    {
        return $this->translations()
                    ->whereHas('language', fn($q) => $q->where('code', $langCode))
                    ->first();
    }
}

