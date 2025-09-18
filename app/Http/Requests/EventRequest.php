<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Можно добавить авторизацию, если нужно
    }

    public function rules()
    {
        return [
            'slug' => 'required|string|unique:events,slug',
            'type' => 'required|in:online,offline,hybrid',
            'location' => 'nullable|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'created_by' => 'required|exists:users,id',
            'all_day' => 'boolean|default:false',

            'translations' => 'required|array|min:1',
            'translations.*.language_id' => 'required|exists:languages,id',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ];
    }
}
