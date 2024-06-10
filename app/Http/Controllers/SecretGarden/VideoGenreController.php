<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;


class VideoGenreController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        $cacheKey = 'video_genres_' . md5($search);

        $genres = Cache::remember($cacheKey, 3600, function () use ($search) {
            return VideoGenre::when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
                ->withCount('videos')
                ->paginate(50);
        });

        return view('secretgarden.video_genres.index', compact('genres'));
    }

    public function show($id, Request $request)
    {
        $genre = VideoGenre::findOrFail($id);
        $sortField = $request->get('sort', 'release_date');
        $sortDirection = $request->get('direction', 'desc');

        $cacheKey = 'genre_videos_' . $id . '_' . $sortField . '_' . $sortDirection;

        $videos = Cache::remember($cacheKey, 3600, function () use ($id, $sortField, $sortDirection) {
            return Video::whereIn('id', function($query) use ($id) {
                $query->select('video_id')
                    ->from('video_genre_video')
                    ->where('genre_id', $id);
            })->orderBy($sortField, $sortDirection)
                ->paginate(50);
        });

        return view('secretgarden.video_genres.show', compact('genre', 'videos', 'sortField', 'sortDirection'));
    }
}





