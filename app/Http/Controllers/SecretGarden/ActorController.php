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
        $actors = Actor::all();
        return view('secretgarden.actor.index', compact('actors'));
    }

    public function show($id)
    {
        $actor = Actor::with('videos')->findOrFail($id);
        return view('secretgarden.actor.show', compact('actor'));
    }
}
