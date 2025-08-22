<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_can_change' => ['required', 'boolean'],
            'duration_minutes_min' => ['nullable', 'integer', 'min:1'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'after:duration_minutes_min'],
            'status' => ['required', 'boolean'],
            'has_fixed_time' => ['required', 'boolean'],
            'time_from' => ['nullable', 'required_if:has_fixed_time,true', 'date_format:H:i'],
            'time_to' => ['nullable', 'required_if:has_fixed_time,true', 'date_format:H:i', 'after:time_from'],
            'eventColor' => ['required', 'string'],
            'translations' => ['required', 'array', 'min:1'],
        ];

        // Динамические правила для переводов
        if ($this->has('translations')) {
            foreach ($this->input('translations') as $index => $translation) {
                $locale = $translation['locale'] ?? null;

                $rules["translations.$index.locale"] = ['required', 'string', 'size:2'];

                if ($locale === 'en') {
                    $rules["translations.$index.name"] = ['required', 'string', 'max:255'];
                } else {
                    $rules["translations.$index.name"] = ['nullable', 'string', 'max:255'];
                }

                $rules["translations.$index.short_description"] = ['nullable', 'string', 'max:500'];
                $rules["translations.$index.full_description"] = ['nullable', 'string'];
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Выберите категорию',
            'category_id.exists' => 'Выбранная категория не существует',
            'price.required' => 'Укажите цену услуги',
            'price.numeric' => 'Цена должна быть числом',
            'price.min' => 'Цена не может быть отрицательной',
            'duration_minutes.required' => 'Укажите продолжительность услуги',
            'duration_minutes.integer' => 'Продолжительность должна быть целым числом',
            'duration_minutes.min' => 'Продолжительность должна быть не менее 1 минуты',
            'status.required' => 'Укажите статус услуги',
            'has_fixed_time.required' => 'Укажите, фиксированное ли время',
            'time_from.required_if' => 'Укажите время начала для фиксированного времени',
            'time_to.required_if' => 'Укажите время окончания для фиксированного времени',
            'time_to.after' => 'Время окончания должно быть после времени начала',
            'eventColor.required' => 'Выберите цвет для услуги',
            'translations.required' => 'Необходимо указать хотя бы один перевод',
            'translations.min' => 'Необходимо указать хотя бы один перевод',
            'translations.*.locale.required' => 'Укажите язык перевода',
            'translations.*.name.required' => 'Укажите название услуги',
            'translations.*.name.max' => 'Название не должно превышать 255 символов',
            'translations.*.short_description.max' => 'Краткое описание не должно превышать 500 символов',
        ];
    }

    public function attributes(): array
    {
        return [
            'translations.*.name' => 'название',
            'translations.*.short_description' => 'краткое описание',
            'translations.*.full_description' => 'полное описание',
        ];
    }
}