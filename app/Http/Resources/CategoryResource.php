<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $otherTranslations = $this->translations()->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'category_id' => $item->category_id,
                'locale' => $item->locale,
                'name' => $item->name,
                'description' => $item->description,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });



        return [
            'id' => $this->id,
            'name' => $this->name,
            'parent_id' => $this->parent_id,
            'order' => $this->order,
            'description' => $this->description,
            'children' => CategoryResource::collection($this->whenLoaded('childrenRecursive')),
            'translations' => $otherTranslations,
            'status'    => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'formatted_created_at' => $this->formatDate($this->created_at),
            'formatted_updated_at' => $this->formatDate($this->updated_at),
        ];
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