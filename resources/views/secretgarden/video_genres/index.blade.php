<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\VideoSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VideoSeriesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $cacheKey = 'video_series_' . md5($search);

        $series = Cache::remember($cacheKey, 600, function () use ($search) {
            return VideoSeries::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
                ->withCount('videos')
                ->paginate(50);
        });

        return view('secretgarden.video_series.index', compact('series'));
    }

    public function show($id, Request $request)
    {
        $series = VideoSeries::findOrFail($id);
        $sortField = $request->get('sort', 'release_date');
        $sortDirection = $request->get('direction', 'desc');

        $cacheKey = 'series_videos_' . $id . '_' . $sortField . '_' . $sortDirection;

        $videos = Cache::store('redis')->remember($cacheKey, 600, function () use ($id, $sortField, $sortDirection) {
            return Video::whereIn('id', function ($query) use ($id) {
                $query->select('video_id')
                    ->from('video_series_video')
                    ->where('series_id', $id);
            })->orderBy($sortField, $sortDirection)
                ->paginate(20);
        });

        return view('secretgarden.video_series.show', compact('series', 'videos', 'sortField', 'sortDirection'));
    }
}
