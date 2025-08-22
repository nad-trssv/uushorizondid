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
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'start_at'     => 'required|date',
            'end_at'       => 'nullable|date|after_or_equal:start_at',
            'location'     => 'nullable|string|max:255',
            'all_day'      => 'boolean',
            'repeat_yearly'=> 'boolean',
        ];
    }
}
