<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorkTimeExceptionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Можно добавить авторизацию при необходимости
    }

    public function rules()
    {
        return [
            'date' => 'required|date',
            'is_full_day' => 'required|boolean',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'repeat_annually' => 'required|boolean',
            'reason' => 'nullable|string|max:255',
        ];
    }
}
