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
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'status' => $this->status,
            'user_id' => $this->user_id,
            'views' => $this->views,
            'current_lang' => $lang,

            'title' => $translation?->title,
            'description' => $translation?->description,
            'image' => $this->image,

            'seo' => $this->seo?->translations->map(fn($seoTranslation) => [
                'language' => $seoTranslation->language->code,
                'meta_title' => $seoTranslation->meta_title,
                'meta_description' => $seoTranslation->meta_description,
                'meta_keywords' => $seoTranslation->meta_keywords,
            ]),

            'gallery' => $this->gallery->map(fn($image) => [
                'image' => $image->image,
                'alt' => $image->alt,
            ]),

            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'role' => $this->user->role->name,
            ],
            'translations' => $this->translations->map(fn($t) => [
                'language' => $t->language->code,
                'default' => $t->language->code === config('app.fallback_locale'),
                'title' => $t->title,
                'description' => $t->description,
            ]),
            'comments_count' => $this->commentsCount(),
            'comments' => $this->comments()->get()->map(fn($comment) => [
                'id' => $comment->id,
                'name' => $comment->name,
                'rating' => $comment->rating,
                'content' => $comment->content,
                
                'created_at' => $comment->created_at->toDateTimeString(),
                'approved' => $comment->approved,
            ]),
            'averageRating' => round($this->averageRating(), 1),
        ];
    }
}
