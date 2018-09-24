<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Artwork;

class Entry extends AbstractModel
{

    protected $casts = [
        'is_copyrighted' => 'boolean',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_entry')->withPivot('weight');
    }

    public function artworks()
    {
        return $this->belongsToMany(Artwork::class, 'entry_artwork');
    }

}
