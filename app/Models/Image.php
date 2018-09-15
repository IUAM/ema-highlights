<?php

namespace App\Models;

use App\Models\Artwork;

class Image extends AbstractModel
{

    public function artworks() {
        return $this->belongsToMany(Artwork::class, 'entry_artwork');
    }

}
