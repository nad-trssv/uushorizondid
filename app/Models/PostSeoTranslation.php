<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostSeoTranslation extends Model
{
    protected $fillable = [
        'post_seo_id',
        'language_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public function seo()
    {
        return $this->belongsTo(PostSeo::class, 'post_seo_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}

