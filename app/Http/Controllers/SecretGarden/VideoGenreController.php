<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\VideoGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VideoGenreController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');

        // 生成缓存键
        $cacheKey = 'video_genres_' . md5($search);

        // 从缓存中获取数据，缓存时间设置为10分钟
        $genres = Cache::remember($cacheKey, 600, function () use ($search) {
            return VideoGenre::when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
                ->withCount('videos') // 使用关系表统计视频数量
                ->paginate(50);
        });

        return view('secretgarden.video_genres.index', compact('genres'));
    }

    public function show($id, Request $request)
    {
        $genre = VideoGenre::findOrFail($id);
        $sortField = $request->get('sort', 'release_date');
        $sortDirection = $request->get('direction', 'desc');

        $videos = Video::whereIn('id', function($query) use ($id) {
            $query->select('video_id')
                ->from('video_genre_video')
                ->where('genre_id', $id);
        })->orderBy($sortField, $sortDirection)
            ->paginate(50);

        return view('secretgarden.video_genres.show', compact('genre', 'videos', 'sortField', 'sortDirection'));
    }
}








