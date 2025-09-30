<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Language;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $post = $this->route('post');

        return [
            'slug'         => ['nullable', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($post?->id)],
            'status'       => ['required', Rule::in(['draft', 'published'])],
            'image'        => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],

            'translations' => ['required', 'array', 'min:1'],
            'translations.*.language_id' => ['required', 'integer', 'exists:languages,id'],
            'translations.*.title'       => ['nullable', 'string', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],

            'seo' => ['required', 'array', 'min:1'],
            'seo.*.language_id'      => ['required', 'integer', 'exists:languages,id'],
            'seo.*.meta_title'       => ['nullable', 'string', 'max:255'],
            'seo.*.meta_description' => ['nullable', 'string', 'max:500'],
            'seo.*.meta_keywords'    => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator($validator): void
    {
        $defaultLangId = Language::where('is_default', true)->value('id');

        // Требуем title на языке по умолчанию
        foreach ($this->input('translations', []) as $i => $row) {
            $isDefault = isset($row['language_id']) && (int)$row['language_id'] === (int)$defaultLangId;
            if ($isDefault) {
                $validator->sometimes("translations.$i.title", ['required'], fn() => true);
            }
        }

        $validator->after(function ($v) use ($defaultLangId) {
            $hasDefault = collect($this->input('translations', []))
                ->contains(fn($t) => (int)($t['language_id'] ?? 0) === (int)$defaultLangId);
            if (!$hasDefault) {
                $v->errors()->add('translations', 'Должен присутствовать перевод на язык по умолчанию.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'Такой slug уже используется.',
            'translations.*.language_id.required' => 'Поле language_id обязательно в каждом переводе.',
            'translations.*.title.required' => 'Заголовок обязателен на языке по умолчанию.',
            'seo.*.language_id.required' => 'Поле language_id обязательно в каждом SEO-переводе.',
        ];
    }
}
