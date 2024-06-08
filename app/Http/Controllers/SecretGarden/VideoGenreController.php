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
        \Log::info('Entered VideoGenreController@index');
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
}







