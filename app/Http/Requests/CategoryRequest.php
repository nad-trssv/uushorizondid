<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'status' => ['required', 'boolean'],
            'translations' => ['required', 'array', 'min:1'],
        ];

        if ($this->has('translations')) {
            foreach ($this->input('translations') as $index => $translation) {
                $locale = $translation['locale'] ?? null;

                $rules["translations.$index.locale"] = ['required', 'string', 'size:2'];

                if ($locale === 'en') {
                    $rules["translations.$index.name"] = ['required', 'string', 'max:255'];
                } else {
                    $rules["translations.$index.name"] = ['nullable', 'string', 'max:255'];
                }

                $rules["translations.$index.description"] = ['nullable', 'string'];
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'parent_id.integer' => 'Родительская категория должна быть числовым идентификатором',
            'parent_id.exists' => 'Выбранная родительская категория не существует',
            'status.required' => 'Укажите статус категории',
            'translations.required' => 'Необходимо указать хотя бы один перевод',
            'translations.min' => 'Необходимо указать хотя бы один перевод',
            'translations.*.locale.required' => 'Укажите язык перевода',
            'translations.*.name.required' => 'Укажите название категории',
            'translations.*.name.max' => 'Название не должно превышать 255 символов',
        ];
    }

    public function attributes(): array
    {
        return [
            'translations.*.name' => 'название',
            'translations.*.description' => 'описание',
        ];
    }
}