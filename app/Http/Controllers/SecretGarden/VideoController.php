<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Cache::remember('videos_all', 60, function () {
            return Video::all();
        });

        return view('secretgarden.video.index', compact('videos'));
    }

    public function show($id)
    {
        $video = Video::findOrFail($id);
        return view('secretgarden.video.show', compact('video'));
    }

    public function store(Request $request)
    {
        $video = Video::create($request->all());

        Video::cacheToRedis();

        return redirect()->back()->with('success', '视频创建成功');
    }

    public function update(Request $request, Video $video)
    {
        $video->update($request->all());

        Video::cacheToRedis();

        return redirect()->back()->with('success', '视频更新成功');
    }

    public function destroy(Video $video)
    {
        $video->delete();

        Video::cacheToRedis();

        return redirect()->back()->with('success', '视频删除成功');
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $cacheKey = "videos_search_{$query}";

        // 检查缓存
        $videos = Cache::get($cacheKey);

        if (!$videos) {
            // 如果缓存不存在，从数据库中查询
            $videos = Video::where('item_number', 'LIKE', "%{$query}%")
                ->orWhere('actor_name', 'LIKE', "%{$query}%")
                ->get();

            // 将结果存入缓存
            Cache::put($cacheKey, $videos, now()->addMinutes(3600)); // 缓存10分钟
        }

        return view('secretgarden.video.search_results', compact('videos'));
    }
}
