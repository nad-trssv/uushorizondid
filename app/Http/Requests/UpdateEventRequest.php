<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => 'required|string|unique:events,slug,' . $this->event->id,
            'status' => 'required|in:draft,published,archived,cancelled',
            'image' => 'nullable|string',
            'max_participants' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'registration_deadline' => 'nullable|date',
            
            'translations' => 'required|array',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.short_description' => 'required|string|max:500',
            'translations.*.full_description' => 'required|string',
            'translations.*.location' => 'nullable|string|max:255',
            'translations.*.requirements' => 'nullable|string',
            'translations.*.included' => 'nullable|string',
            
            'seo' => 'nullable|array',
            'seo.*.language_id' => 'required|exists:languages,id',
            'seo.*.meta_title' => 'nullable|string|max:255',
            'seo.*.meta_description' => 'nullable|string|max:500',
            'seo.*.meta_keywords' => 'nullable|string|max:255',
        ];
    }
}