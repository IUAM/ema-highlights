<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Category;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        return view('home', [
            'title' => 'Collection Highlights',
            'category' => Category::find(1),
            'background' => with(new Image)->getFull(388),
        ]);
    }

    public function random($page)
    {
        // This will be our random seed, rounded to nearest hour
        $now = date('U');
        $now = $now - ($now % 1800);

        $limit = 24;
        $offset = $page * $limit;

        // Retrieve random entries which have descriptions and images
        $query = "
            SELECT
                `entries`.id AS id,
                `artwork_image`.image_id AS tn,
                `images`.base64_sq AS b64
            FROM `entries`
                INNER JOIN `entry_artwork`
                    ON `entry_artwork`.entry_id = `entries`.id
                INNER JOIN `artwork_image`
                    ON `artwork_image`.artwork_id = `entry_artwork`.artwork_id
                INNER JOIN `images`
                    ON `images`.id = `artwork_image`.image_id
            WHERE
                `entries`.description != ''
            ORDER BY RAND(?)
            LIMIT ?,?
        ";

        $results = DB::select($query, [$now, $offset, $limit]);

        // Prepare the results for presentation
        $results = collect($results)->map( function($item) {
            return [
                'page' => route('entry', $item->id),
                'tn' => with(new Image)->getSquareThumbnail($item->tn),
                'b64' => 'data:image/gif;base64,R0lGODlh' . $item->b64,
            ];
        });

        return $results;
    }

}
