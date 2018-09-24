<?php

namespace App\Http\Controllers;

use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        //
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        // This assumes that categories only nest one level deep...
        if (!$category->children()->exists())
        {
            return $this->showSubCategory($category);
        }

        $out = [
            'title' => $category->title,
            'category' => $category,
            'children' => $category->children,
        ];

        return view('category-top', $out);
    }

    private function showSubCategory($category)
    {
        $entries = $category->entries()->with('artworks', 'artworks.images')->paginate(20);

        $out = [
            'title' => $category->title,
            'category' => $category,
            'entries' => $entries,
        ];

        // return $out;

        return view('category-sub', $out);
    }

}
