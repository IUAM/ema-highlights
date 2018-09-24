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

    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class);
    }

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

    /**
     * Returns all categories, but with `children` filled out all the way down.
     *
     * If a collection is provided as the first argument, it'll use that as the
     * basis for nesting, rather than querying the database. Useful for reducing
     * database queries, or for getting a nested subset below a certain root.
     *
     * @link http://stackoverflow.com/a/8587437
     *
     * @param \Illuminate\Support\Collection $categories (Optional)
     * @param integer $parent_id
     *
     * @return array
     */
    public static function getAllNested($categories = null, $parent_id = null)
    {
        $categories = $categories ?? self::all();

        $branch = collect();

        foreach ($categories as $category)
        {
            if ($category->category_id == $parent_id)
            {
                $category->children = self::getAllNested($categories, $category->id);
                $branch->push($category);
            }
        }

        return $branch;
    }

}
