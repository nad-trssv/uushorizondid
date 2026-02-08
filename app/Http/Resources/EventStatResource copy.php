<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventStatResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'total_count' => $this['total_count'] ?? 0,
            'total_views' => $this['total_views'] ?? 0,
            'total_participants' => $this['total_participants'] ?? 0,
            'total_confirmed_participants' => $this['total_confirmed_participants'] ?? 0,
            
            'by_status' => $this['by_status'] ?? [],
            
            'most_popular_event' => new EventResource($this['most_popular_event']) ?? null,
            'most_viewed_event' => new EventResource($this['most_viewed_event']) ?? null,
            'most_participated_event' => new EventResource($this['most_participated_event']) ?? null,
            
            'upcoming_events' => $this['upcoming_events'] ?? 0,
            'past_events' => $this['past_events'] ?? 0,
            
            'average_participation_rate' => $this['average_participation_rate'] ?? 0,
            
            'monthly_comparison' => $this['monthly_comparison'] ?? [
                'current' => 0,
                'previous' => 0,
                'difference' => 0,
                'percentage' => 0
            ],
            
            'yearly_comparison' => $this['yearly_comparison'] ?? [
                'current' => 0,
                'previous' => 0,
                'difference' => 0,
                'percentage' => 0
            ],
            
            'monthly_participants' => $this['monthly_participants'] ?? [],
            'monthly_events' => $this['monthly_events'] ?? [],
            
            'recent_events' => $this['recent_events'] ?? 0,
            'recent_participants' => $this['recent_participants'] ?? 0,
        ];
    }
}