<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ActorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sortField = $request->input('sortField', 'name');
        $sortDirection = $request->input('sortDirection', 'asc');

        // 生成缓存键
        $cacheKey = "actors_search_{$search}_sort_{$sortField}_{$sortDirection}";

        // 尝试从缓存中获取数据
        $actors = Cache::remember($cacheKey, 3600, function () use ($search, $sortField, $sortDirection) {
            return Actor::when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('english_name', 'like', "%{$search}%");
            })
                ->orderBy($sortField, $sortDirection)
                ->paginate(50);
        });

        return view('secretgarden.actor.index', compact('actors', 'sortField', 'sortDirection'));
    }

    public function show($id, Request $request)
    {
        $sortField = $request->input('sort', 'release_date');
        $sortDirection = $request->input('direction', 'desc');

        $actor = Cache::remember("actor_{$id}", 3600, function () use ($id) {
            return Actor::findOrFail($id);
        });

        $videos = Video::where('actor_id', $actor->id)
            ->orderBy($sortField, $sortDirection)
            ->paginate(50);

        if ($request->ajax()) {
            return response()->json($videos);
        }

        return view('secretgarden.actor.show', compact('actor', 'videos', 'sortField', 'sortDirection'));
    }
}



