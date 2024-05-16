<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\Actor;
use Illuminate\Http\Request;
use App\Models\Torrent;


class ActorController extends Controller
{
    public function index()
    {
        $actor = Actor::all();
        return view('actor.index', compact('actor'));
    }

    public function show($id)
    {
        $actor = Actor::findOrFail($id);
        $torrents = Torrent::where('name', 'like', '%'.$actor->name.'%')
            ->where('category_id', 3)
            ->get();

        return view('actor.show', compact('actor', 'torrents'));
    }
}
