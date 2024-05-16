<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use Illuminate\Http\Request;
use App\Models\Torrent;

class ActorController extends Controller
{
    public function index()
    {
        $actors = Actor::all();
        return view('actors.index', compact('actors'));
    }

    public function show($id)
    {
        $actor = Actor::findOrFail($id); // 修改为正确的模型名称
        $torrents = Torrent::where('name', 'like', '%'.$actor->name.'%')
            ->where('category_id', 3)
            ->get();

        return view('actors.show', compact('actor', 'torrents'));
    }
}