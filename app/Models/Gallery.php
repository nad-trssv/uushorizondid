<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['image', 'alt', 'galleryable_id', 'galleryable_type'];

    public function galleryable()
    {
        return $this->morphTo();
    }
}
