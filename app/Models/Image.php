<?php

namespace App\Models;

use App\Models\Artwork;

class Image extends AbstractModel
{

    public function artworks()
    {
        return $this->belongsToMany(Artwork::class, 'artwork_image');
    }

    public function getIsCopyrightedAttribute($value)
    {
        return $this->artworks()->get(['is_copyrighted'])->pluck('is_copyrighted')->first(function($value) {
            return $value;
        }) ?? false;
    }

    /**
     * TODO: Store `aspect` in the database!
     */
    public function getAspectAttribute()
    {
        $file = storage_path('app/public/objects/tn/' . $this->id . '.jpg');
        $size = getimagesize( $file );
        $aspect = max($size[0], $size[1]) / min($size[0], $size[1]);

        return $aspect;
    }

    public function getFull()
    {
        return asset('storage/objects/' . ($this->is_copyrighted ? 'tn' : 'full') . '/' . $this->id . '.jpg');
    }

    public function getSquareThumbnail()
    {
        return asset('storage/objects/sq/' . $this->id . '.jpg');
    }

}
