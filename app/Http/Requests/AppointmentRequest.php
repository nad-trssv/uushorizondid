<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_name'   => 'required|string|max:255',
            'client_lastname' => 'required|string|max:255',
            'client_phone'  => 'required|string|max:20',
            'client_email'  => 'required|email|max:255',
            'service_id'    => 'required|exists:services,id',
            'user_id'       => 'required|exists:users,id',
            'price'         => 'required|numeric|min:0',
            'appointment_start' => 'required|date_format:H:i',
            'appointment_end'   => 'required|date_format:H:i|after:appointment_start',
            'description'   => 'nullable|string|max:1000',
        ];
    }
}
