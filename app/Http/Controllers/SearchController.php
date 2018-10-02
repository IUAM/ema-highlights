<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Entry;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search()
    {
        $query = Input::get('q');

        // 302 to home if no query specified
        if (empty($query))
        {
            return redirect()->route('home');
        }

        // Relies on the FullTextSearchable trait
        $entries = Entry::search($query)->paginate(20)->appends('q', $query);

        // Build out our "category" to fill the header
        $category = new \stdClass();
        $category->title = 'Search for <i>' . htmlspecialchars($query) . '</i>';
        $category->description = '<p>' . $entries->total() . ' matches from description or tombstone.';
        $category->tn_image_id = '00';

        $out = [
            'title' => $category->title,
            'category' => $category,
            'entries' => $entries,
        ];

        return view('category-sub', $out);
    }
}
