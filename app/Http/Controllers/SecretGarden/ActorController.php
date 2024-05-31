<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ActorController extends Controller
{
    public function index()
    {
        // 尝试从缓存中获取演员数据，如果不存在则查询数据库并缓存结果
        $actors = Cache::remember('actors', 3600, function () {
            return Actor::all();
        });

        return view('secretgarden.actor.index', compact('actors'));
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
