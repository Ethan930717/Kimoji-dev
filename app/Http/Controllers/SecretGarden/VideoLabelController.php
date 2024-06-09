<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VideoLabelController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $cacheKey = 'video_labels_' . md5($search);

        $labels = Cache::remember($cacheKey, 600, function () use ($search) {
            return VideoLabel::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
                ->withCount('videos')
                ->paginate(50);
        });

        return view('secretgarden.video_labels.index', compact('labels'));
    }

    public function show($id, Request $request)
    {
        $label = VideoLabel::findOrFail($id);
        $sortField = $request->get('sort', 'release_date');
        $sortDirection = $request->get('direction', 'desc');

        $cacheKey = 'label_videos_' . $id . '_' . $sortField . '_' . $sortDirection;

        $videos = Cache::store('redis')->remember($cacheKey, 600, function () use ($id, $sortField, $sortDirection) {
            return Video::whereIn('id', function ($query) use ($id) {
                $query->select('video_id')
                    ->from('video_label_video')
                    ->where('label_id', $id);
            })->orderBy($sortField, $sortDirection)
                ->paginate(50);
        });

        return view('secretgarden.video_labels.show', compact('label', 'videos', 'sortField', 'sortDirection'));
    }
}
