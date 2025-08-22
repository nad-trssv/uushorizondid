<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceShowResource extends JsonResource
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
        $otherTranslations = $this->translationsAll()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'service_id' => $item->service_id,
                'locale' => $item->locale,
                'name' => $item->name,
                'short_description' => $item->short_description,
                'full_description' => $item->full_description,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });

        return [
            'id' => $this->id,
            'eventColor' => $this->eventColor,
            'name' => $translation ? $translation->name : null,
            'price' => number_format($this->price, 2),
            'price_can_change' => $this->price_can_change,
            'category_id' => $this->category_id,
            'duration_minutes_min' => $this->duration_minutes_min,
            'duration_minutes' => $this->duration_minutes,
            'status' => $this->status,
            'time_from' => $this->formatTime($this->time_from),
            'time_to' => $this->formatTime($this->time_to),
            'has_fixed_time' => $this->has_fixed_time,
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->formatDate($this->created_at),
            'updated_at' => $this->formatDate($this->updated_at),
            'translations' => $otherTranslations,
            'masters' => $this->serviceMasters->map(function ($master) {
                return [
                    'id' => $master->id,
                    'service_id' => $master->service_id,
                    'user_id' => $master->user_id,
                    'user_name' => $master->user ? $master->user->name : null,
                ];
            }),
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
