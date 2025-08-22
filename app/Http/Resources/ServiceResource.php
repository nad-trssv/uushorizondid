<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Преобразование ресурса в массив.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $translation = $this->translations->first();

        return [
            'id' => $this->id,
            'name' => $translation ? $translation->name : null,
            'eventColor' => $this->eventColor,
            'price' => number_format($this->price, 2), // Форматируем цену
            'price_can_change' => $this->price_can_change,
            'category_id' => $this->category_id,
            'category_name' => $this->category ? $this->category->translation()->name : null,
            'duration_minutes_min' => $this->duration_minutes_min,
            'duration_minutes' => $this->duration_minutes,
            'short_description' => $translation ? $translation->short_description : null,
            'full_description' => $translation ? $translation->full_description : null,
            'status' => $this->status,
            'time_from' => $this->formatTime($this->time_from),
            'time_to' => $this->formatTime($this->time_to),
            'has_fixed_time' => $this->has_fixed_time,
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
        ];
    }
    
    private function formatTime($time): ?string
    {
        return $time ? Carbon::parse($time)->format('H:i') : null;
    }

    /**
     * Форматирование даты.
     *
     * @param string|null $date
     * @return string|null
     */
    private function formatDate($date): ?string
    {
        return $date ? Carbon::parse($date)->format('d-m-Y H:i') : null;
    }
}
