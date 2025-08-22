<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'client_name' => $this->client_name,
            'client_lastname' => $this->client_lastname,
            'client_phone' => $this->client_phone,
            'client_email' => $this->client_email,
            'service_id' => $this->service_id,
            'user_id' => $this->user_id,
            'price' => number_format($this->price, 2), // Format price
            'status' => $this->status,
            'appointment_date' => $this->appointment_date ? $this->formatDate($this->appointment_date) : null,
            'appointment_start' => $this->formatTime($this->appointment_start),
            'appointment_end' => $this->formatTime($this->appointment_end),
            'description' => $this->description,
            'service' => new ServiceResource($this->whenLoaded('service')),
            'created_at' => $this->formatDateTime($this->created_at),
            'updated_at' => $this->formatDateTime($this->updated_at),
        ];
    }
    private function formatTime($time): ?string
    {
        return $time ? Carbon::parse($time)->format('H:i') : null;
    }
    private function formatDateTime($date): ?string
    {
        return $date ? Carbon::parse($date)->format('d-m-Y H:i') : null;
    }
    private function formatDate($date): ?string
    {
        return $date ? Carbon::parse($date)->format('d-m-Y') : null;
    }
}
