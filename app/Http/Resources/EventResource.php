<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            
            'views' => $this->views,
            'current_lang' => $lang,

            // Основные поля мероприятия
            'max_participants' => $this->max_participants,
            'current_participants' => $this->confirmedParticipantsCount(),
            'available_spots' => $this->availableSpots(),
            'price' => (float) $this->price,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'registration_deadline' => $this->registration_deadline,
            'is_registration_open' => $this->isRegistrationOpen(),
            'has_available_spots' => $this->hasAvailableSpots(),

            // Переводы
            'title' => $translation?->title ?? $this->translations->firstWhere('language.is_default', true)?->title,
            'short_description' => $translation?->short_description ?? $this->translations->firstWhere('language.is_default', true)?->short_description,
            'full_description' => $translation?->full_description ?? $this->translations->firstWhere('language.is_default', true)?->full_description,
            'location' => $translation?->location ?? $this->translations->firstWhere('language.is_default', true)?->location,
            'requirements' => $translation?->requirements ?? $this->translations->firstWhere('language.is_default', true)?->requirements,
            'included' => $translation?->included ?? $this->translations->firstWhere('language.is_default', true)?->included,
            'image' => $this->image,

            // SEO
            'seo' => $this->seo?->translations->map(fn($seoTranslation) => [
                'language' => $seoTranslation->language->code,
                'meta_title' => $seoTranslation->meta_title,
                'meta_description' => $seoTranslation->meta_description,
                'meta_keywords' => $seoTranslation->meta_keywords,
            ]),

            // Галерея
            'gallery' => $this->gallery->map(fn($image) => [
                'image' => $image->image,
                'alt' => $image->alt,
            ]),

            // Все переводы
            'translations' => $this->translations->map(fn($t) => [
                'language' => $t->language->code,
                'default' => $t->language->code === config('app.fallback_locale'),
                'title' => $t->title,
                'short_description' => $t->short_description,
                'full_description' => $t->full_description,
                'location' => $t->location,
                'requirements' => $t->requirements,
                'included' => $t->included,
            ]),

            // Статистика участников
            'participants_count' => $this->participantsCount(),
            'confirmed_participants_count' => $this->confirmedParticipantsCount(),

            // Участники (только для админки)
            'participants' =>  
                $this->participants->map(fn($participant) => [
                    'id' => $participant->id,
                    'first_name' => $participant->first_name,
                    'last_name' => $participant->last_name,
                    'full_name' => $participant->full_name,
                    'email' => $participant->email,
                    'phone' => $participant->phone,
                    'status' => $participant->status,
                    'participants_count' => $participant->participants_count,
                    'notes' => $participant->notes,
                    'created_at' => $participant->created_at->toDateTimeString(),
                ]
            ),
        ];
    }
}