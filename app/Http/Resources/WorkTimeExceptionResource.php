<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkTimeExceptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'date' => $this->date->format('Y-m-d'),
            'start_time' => $this->start_time !== null ? $this->start_time->format('H:i') : null,
            'end_time' => $this->end_time !== null ? $this->end_time->format('H:i') : null,
            'is_full_day' => $this->is_full_day,
            'repeat_annually' => $this->repeat_annually,
            'reason' => $this->reason,
        ];
    }
}
