<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Language;

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
            // БАЗОВО: nullable; "required" добавим точечно в withValidator()
            'translations.*.title'             => ['nullable', 'string', 'max:255'],
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
        ];
    }

    /**
     * Делаем title обязательным только для записи с языком по умолчанию (is_default = true).
     * Не полагаемся на поле "default" из входных данных — сверяемся с БД.
     */
    public function withValidator($validator): void
    {
        $defaultLangId = Language::where('is_default', true)->value('id');

        $translations = $this->input('translations', []);
        foreach ($translations as $i => $row) {
            $isDefaultLang = isset($row['language_id']) && (int)$row['language_id'] === (int)$defaultLangId;

            // Если это язык по умолчанию — требуем title
            if ($isDefaultLang) {
                $validator->sometimes("translations.$i.title", ['required'], fn () => true);
            }
        }

        // Дополнительно: проверим, что среди translations вообще есть элемент с language_id = языку по умолчанию
        $validator->after(function ($v) use ($defaultLangId, $translations) {
            $hasDefaultLang = collect($translations)->contains(fn ($t) => (int)($t['language_id'] ?? 0) === (int)$defaultLangId);
            if (!$hasDefaultLang) {
                $v->errors()->add('translations', 'Должен присутствовать перевод на язык по умолчанию.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'slug.required' => 'Slug обязателен.',
            'slug.unique'   => 'Такой slug уже используется.',
            'start_time.required' => 'Дата начала обязательна.',
            'end_time.required'   => 'Дата окончания обязательна.',
            'end_time.after'      => 'Дата окончания должна быть позже даты начала.',
            'translations.*.language_id.required' => 'Поле language_id обязательно в каждом переводе.',
            'translations.*.title.required' => 'Название обязательно на языке по умолчанию.',
            'seo.*.language_id.required' => 'Поле language_id обязательно в каждом SEO-переводе.',
        ];
    }
}
