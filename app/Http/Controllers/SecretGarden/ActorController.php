<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\Actor;
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
            return Actor::withCount(['videos' => function($query) use ($search) {
                if ($search) {
                    $query->where('name', 'like', "%{$search}%");
                }
            }])->orderBy($sortField, $sortDirection)->paginate(10);
        });

        return view('secretgarden.actor.index', compact('actors', 'sortField', 'sortDirection'));
    }

    public function show($id)
    {
        // 尝试从缓存中获取特定演员数据，如果不存在则查询数据库并缓存结果
        $actor = Cache::remember("actor_{$id}", 3600, function () use ($id) {
            return Actor::with('videos')->findOrFail($id);
        });

        return view('secretgarden.actor.show', compact('actor'));
    }
}

