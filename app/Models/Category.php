<?php

namespace App\Models;

use App\Models\Entry;

class Category extends AbstractModel
{

    public function entries() {
        return $this->belongsToMany(Entry::class, 'category_entry');
    }

}
