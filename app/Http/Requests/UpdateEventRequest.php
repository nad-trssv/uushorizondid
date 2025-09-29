<?php

// app/Http/Requests/UpdateEventRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $event = $this->route('event'); // Route Model Binding

        return [
            'slug'   => ['required', 'string', 'max:255', Rule::unique('events', 'slug')->ignore($event?->id)],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'image'  => ['nullable', 'string', 'max:255'],

            'max_participants' => ['required', 'integer', 'min:1'],
            'price'            => ['required', 'numeric', 'min:0'],

            'start_time' => ['required', 'date'],
            'end_time'   => ['required', 'date', 'after:start_time'],
            'registration_deadline' => ['nullable', 'date', 'before_or_equal:start_time'],

            'translations' => ['required', 'array', 'min:1'],
            'translations.*.language_id'       => ['required', 'integer', 'exists:languages,id'],
            'translations.*.title'             => ['required', 'string', 'max:255'],
            'translations.*.short_description' => ['nullable', 'string'],
            'translations.*.full_description'  => ['nullable', 'string'],
            'translations.*.location'          => ['nullable', 'string', 'max:255'],
            'translations.*.requirements'      => ['nullable', 'string'],
            'translations.*.included'          => ['nullable', 'string'],

            'seo' => ['required', 'array', 'min:1'],
            'seo.*.language_id'      => ['required', 'integer', 'exists:languages,id'],
            'seo.*.meta_title'       => ['nullable', 'string', 'max:255'],
            'seo.*.meta_description' => ['nullable', 'string', 'max:500'],
            'seo.*.meta_keywords'    => ['nullable', 'string', 'max:500'],
            
            'updated_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.required' => 'Slug обязателен.',
            'slug.unique'   => 'Такой slug уже используется.',
            'start_time.required' => 'Дата начала обязательна.',
            'end_time.after'      => 'Дата окончания должна быть позже даты начала.',
            'translations.*.language_id.required' => 'Поле language_id обязательно в каждом переводе.',
            'translations.*.title.required' => 'Название на каждом языке обязательно.',
            'seo.*.language_id.required' => 'Поле language_id обязательно в каждом SEO-переводе.',
        ];
    }
}

