<?php

namespace App\Models;

use App\Models\Entry;
use App\Models\Image;

class Artwork extends AbstractModel
{

    protected $casts = [
        'is_copyrighted' => 'boolean',
    ];

    public function entries() {
        return $this->belongsToMany(Entry::class, 'entry_artwork');
    }

    public function images() {
        return $this->belongsToMany(Image::class, 'artwork_image');
    }

}
