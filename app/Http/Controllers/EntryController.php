<?php

namespace App\Http\Controllers;

use App\Models\Entry;

use Illuminate\Http\Request;

class EntryController extends Controller
{
    public function index()
    {
        //
    }

    public function show($id)
    {
        // TODO: Specify columns on relations?
        $entry = Entry::with('artworks', 'artworks.images')->findOrFail($id);

        $out = [
            'entry' => $entry,
            'title' => $entry->title, // TODO: Add global title?
            'breadcrumbs' => $this->getBreadcrumbs($entry),
            'next' => $this->getAdjacentEntryLink($entry->getNextEntry()),
            'prev' => $this->getAdjacentEntryLink($entry->getPrevEntry()),
            'images' => $entry->artworks->pluck('images')->collapse(),
        ];

        // return $out;

        return view('entry', $out);

    }

    private function getAdjacentEntryLink($entry)
    {
        if (!isset($entry)) {
            return null;
        }

        $image = $entry->artworks->pluck('images')->collapse()->first();

        return [
            'accession' => $entry->accession,
            'image' => $image->getSquareThumbnail(),
            'href' => route('entry', $entry->id),
        ];
    }

    private function getBreadcrumbs($entry)
    {
        return [
            [
                'id' => $entry->id,
                'href' => route('entry', $entry->id),
                'title' => $entry->accession,
            ]
        ];
    }
}
