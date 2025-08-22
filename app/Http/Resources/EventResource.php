<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'start_at'    => $this->start_at->toDateTimeString(),
            'end_at'      => $this->end_at ? $this->end_at->toDateTimeString() : null,
            'location'    => $this->location,
            'all_day'     => $this->all_day,
            'repeat_yearly' => $this->repeat_yearly,
            'created_at'  => $this->created_at->toDateTimeString(),
            'updated_at'  => $this->updated_at->toDateTimeString(),
        ];
    }
}
