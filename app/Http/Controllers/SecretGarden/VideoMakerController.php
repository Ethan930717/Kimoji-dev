<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\VideoMaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VideoMakerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $cacheKey = 'video_makers_' . md5($search);

        $makers = Cache::remember($cacheKey, 600, function () use ($search) {
            return VideoMaker::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
                ->withCount('videos')
                ->paginate(50);
        });

        return view('secretgarden.video_makers.index', compact('makers'));
    }

    public function show($id, Request $request)
    {
        $maker = VideoMaker::findOrFail($id);
        $sortField = $request->get('sort', 'release_date');
        $sortDirection = $request->get('direction', 'desc');

        $cacheKey = 'maker_videos_' . $id . '_' . $sortField . '_' . $sortDirection;

        $videos = Cache::store('redis')->remember($cacheKey, 600, function () use ($id, $sortField, $sortDirection) {
            return Video::whereIn('id', function ($query) use ($id) {
                $query->select('video_id')
                    ->from('video_maker_video')
                    ->where('maker_id', $id);
            })->orderBy($sortField, $sortDirection)
                ->paginate(50);
        });

        return view('secretgarden.video_makers.show', compact('maker', 'videos', 'sortField', 'sortDirection'));
    }
}
