<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Torrent;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::all();
        return view('secretgarden.video.index', compact('videos'));
    }

    public function show($id)
    {
        $video = Video::with('videos')->findOrFail($id);
        return view('secretgarden.video.show', compact('video'));
    }
    public function store(Request $request)
    {
        // 验证和创建视频记录
        $video = Video::create($request->all());

        // 清除相关缓存
        Cache::forget("videos_search_{$request->input('item_number')}");
        Cache::flush(); // 也可以根据需要选择性清除缓存

        return redirect()->back()->with('success', '视频创建成功');
    }

    public function update(Request $request, Video $video)
    {
        // 更新视频记录
        $video->update($request->all());

        // 清除相关缓存
        Cache::forget("videos_search_{$request->input('item_number')}");
        Cache::flush(); // 也可以根据需要选择性清除缓存

        return redirect()->back()->with('success', '视频更新成功');
    }

    public function destroy(Video $video)
    {
        // 删除视频记录
        $video->delete();

        // 清除相关缓存
        Cache::forget("videos_search_{$video->item_number}");
        Cache::flush(); // 也可以根据需要选择性清除缓存

        return redirect()->back()->with('success', '视频删除成功');
    }
}
