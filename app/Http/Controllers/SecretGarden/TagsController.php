<?php

namespace App\Http\Controllers;

use App\Models\VideoGenre;
use Illuminate\Http\Request;

class VideoGenreController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $genres = VideoGenre::when($search, function($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })
            ->withCount('videos')
            ->paginate(50);

        return view('video-genre-search', compact('genres'));
    }
}





