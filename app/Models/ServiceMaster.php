<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class ServiceMaster extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id',
        'user_id',
    ];
    protected $table = 'service_masters';
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeWithServiceAndUser($query)
    {
        return $query->with(['service', 'user']);
    }
}
