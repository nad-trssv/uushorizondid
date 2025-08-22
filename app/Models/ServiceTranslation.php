<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ServiceTranslation extends Model
{
    use HasFactory;

    protected $fillable = [
        'locale',
        'name',
        'short_description',
        'full_description',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
