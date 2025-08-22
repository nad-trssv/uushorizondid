<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
    ];

    public $timestamps = false;

    protected $casts = [
        'value' => 'array',
    ];

    public static function group(string $group)
    {
        return new class($group) {
            public function __construct(private string $group) {}

            public function create(array $data)
            {
                return SiteSetting::create([
                    'group' => $this->group,
                    ...$data,
                ]);
            }
        };
    }
}
