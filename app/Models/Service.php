<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'eventColor',
        'price',
        'price_can_change',
        'category_id',
        'duration_minutes_min',
        'duration_minutes',
        'status',
        'time_from',
        'time_to',
        'has_fixed_time',
        'is_deleted',
    ];

    public function translations()
    {
        return $this->hasMany(ServiceTranslation::class);
    }

    public function translation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return $this->translations()->where('locale', $locale)->first();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function translationsAll()
    {
        return $this->hasMany(ServiceTranslation::class, 'service_id');
    }
    public function serviceMasters()
    {
        return $this->hasMany(ServiceMaster::class);
    }

}
