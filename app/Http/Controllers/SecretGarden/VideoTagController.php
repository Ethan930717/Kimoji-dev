<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VideoTagController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $cacheKey = 'video_tags_' . md5($search);

        $tags = Cache::remember($cacheKey, 600, function () use ($search) {
            return VideoTag::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
                ->withCount('videos')
                ->paginate(50);
        });

        return view('secretgarden.video_tags.index', compact('tags'));
    }

    public function show($id, Request $request)
    {
        $tag = VideoTag::findOrFail($id);
        $sortField = $request->get('sort', 'release_date');
        $sortDirection = $request->get('direction', 'desc');

        $cacheKey = 'tag_videos_' . $id . '_' . $sortField . '_' . $sortDirection;

        $videos = Cache::store('redis')->remember($cacheKey, 600, function () use ($id, $sortField, $sortDirection) {
            return Video::whereIn('id', function ($query) use ($id) {
                $query->select('video_id')
                    ->from('video_tag_video')
                    ->where('tag_id', $id);
            })->orderBy($sortField, $sortDirection)
                ->paginate(50);
        });

        return view('secretgarden.video_tags.show', compact('tag', 'videos', 'sortField', 'sortDirection'));
    }
}
