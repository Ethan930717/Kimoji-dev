<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $sortField = $request->get('sort', 'release_date');
        $sortDirection = $request->get('direction', 'desc');

        $videos = Video::orderBy($sortField, $sortDirection)->paginate(10);

        return view('secretgarden.video.index', compact('videos', 'sortField', 'sortDirection'));
    }

    public function show($id)
    {
        $video = Video::findOrFail($id);
        return view('secretgarden.video.show', compact('video'));
    }

    public function store(Request $request)
    {
        $video = Video::create($request->all());

        // 清除相关缓存
        Cache::forget("videos_search_{$request->input('item_number')}");
        Cache::flush(); // 也可以根据需要选择性清除缓存

        return redirect()->back()->with('success', '视频创建成功');
    }

    public function update(Request $request, Video $video)
    {
        $video->update($request->all());

        // 清除相关缓存
        Cache::forget("videos_search_{$request->input('item_number')}");
        Cache::flush(); // 也可以根据需要选择性清除缓存

        return redirect()->back()->with('success', '视频更新成功');
    }

    public function destroy(Video $video)
    {
        $video->delete();

        // 清除相关缓存
        Cache::forget("videos_search_{$video->item_number}");
        Cache::flush(); // 也可以根据需要选择性清除缓存

        return redirect()->back()->with('success', '视频删除成功');
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $cacheKey = "videos_search_" . md5($query);

        // 检查缓存
        $videos = Cache::get($cacheKey);

        if (!$videos) {
            // 如果缓存不存在，从数据库中查询
            $videos = Video::where('item_number', 'LIKE', "%{$query}%")
                ->get();

            // 将结果存入缓存
            Cache::put($cacheKey, $videos, now()->addMinutes(3600));
        }

        return view('secretgarden.video.search_results', compact('videos'));
    }
}
