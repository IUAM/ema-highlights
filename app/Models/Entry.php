<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Artwork;
use App\Models\Behaviors\FullTextSearchable;

class Entry extends AbstractModel
{
    use FullTextSearchable;

    /**
     * The columns of the full text index
     */
    protected $searchable = [
        'tombstone',
        'description',
    ];

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

    public function getNextEntry($categoryId = null)
    {
        return $this->getAdjacentEntry($categoryId);
    }

    public function getPrevEntry($categoryId = null)
    {
        return $this->getAdjacentEntry($categoryId, true);
    }

    public function getIsCopyrightedAttribute($value)
    {
        return $this->artworks()->get(['is_copyrighted'])->pluck('is_copyrighted')->first( function($value) {
            return $value;
        }) ?? false;
    }

    private function getAdjacentEntry($categoryId = null, $descending = false)
    {
        if (!$categoryId) {
            $category = $this->categories()->firstOrFail(['categories.id']);
        } else {
            $category = $this->categories()->where('categories.id', '=', $categoryId)->firstOrFail(['categories.id']);
        }

        $categoryId = $category->id;
        $currentWeight = $category->pivot->weight;

        // Find all entries with the same category whose pivots' weight is greater or less than this one...
        return Entry::whereHas('categories', function($query) use ($categoryId, $currentWeight, $descending) {
            $query->where('categories.id', $categoryId);
            $query->where('category_entry.weight', $descending ? '<' : '>', $currentWeight);
            $query;
        })
        // ...then order by pivot weight and only grab one
        ->join('category_entry', 'entries.id', '=', 'category_entry.entry_id')
        ->orderBy('category_entry.weight', $descending ? 'desc' : 'asc')
        ->limit(1)
        ->first();
    }

}
