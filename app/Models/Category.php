<?php

namespace App\Models;

use App\Models\Entry;

class Category extends AbstractModel
{

    protected $appends = [
        'title_medium_safe',
        'title_short_safe',
        'title_abbr_safe',
    ];

    public function entries()
    {
        return $this->belongsToMany(Entry::class, 'category_entry')->withPivot('weight');
    }

    public function getTitleMediumSafeAttribute()
    {
        return $this->title_medium ?? $this->title;
    }

    public function getTitleShortSafeAttribute()
    {
        return $this->title_short ?? $this->title_medium_safe;
    }

    public function getTitleAbbrSafeAttribute()
    {
        return $this->title_abbr ?? $this->title_short_safe;
    }

}
