<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray($request)
    {
        $lang = $request->get('lang', app()->getLocale());

        $translation = $this->translations->firstWhere('language.code', $lang);
        $seoTranslation = optional($this->seo?->translations->firstWhere('language.code', $lang));

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'published_at' => $this->published_at,
            'status' => $this->status,
            'user_id' => $this->user_id,

            'title' => $translation?->title,
            'description' => $translation?->description,
            'image' => $this->image,

            'seo' => [
                'meta_title' => $seoTranslation?->meta_title,
                'meta_description' => $seoTranslation?->meta_description,
                'meta_keywords' => $seoTranslation?->meta_keywords,
            ],

            'gallery' => $this->gallery->map(fn($image) => [
                'image' => $image->image,
                'alt' => $image->alt,
            ]),

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
            ],
            'translations' => $this->translations->map(fn($t) => [
                'language' => $t->language->code,
                'default' => $t->language->code === config('app.fallback_locale'),
                'title' => $t->title,
                'description' => $t->description,
            ]),
            'comments_count' => $this->commentsCount(),
            'comments' => $this->comments()->where('approved', true)->get()->map(fn($comment) => [
                'id' => $comment->id,
                'name' => $comment->name,
                'rating' => $comment->rating,
                'content' => $comment->content,
                'created_at' => $comment->created_at->toDateTimeString(),
            ]),
            'averageRating' => round($this->averageRating(), 1),
        ];
    }
}
