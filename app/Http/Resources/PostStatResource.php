<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostStatResource extends JsonResource
{
    public function toArray($request)
    {
        
        return [
            'total_count' => $this['total_count'] ?? 0,
            'total_views' => $this['total_views'] ?? 0,
            'total_comments' => $this['total_comments'] ?? 0,
            
            'by_status' => $this['by_status'] ?? [],
            
            'most_rated_post' => $this['most_rated_post'] ?? null,
            'most_viewed_post' => $this['most_viewed_post'] ?? null,
            'most_commented_post' => $this['most_commented_post'] ?? null,
            
            'with_gallery' => $this['with_gallery'] ?? 0,
            'with_images' => $this['with_images'] ?? 0,
            
            'average_rating' => $this['average_rating'] ?? 0,
            
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
            
            'monthly_views' => $this['monthly_views'] ?? [],
            'monthly_comments' => $this['monthly_comments'] ?? [],
            
            'recent_posts' => $this['recent_posts'] ?? 0,
            'recent_active_posts' => $this['recent_active_posts'] ?? 0,
        ];
    }
}