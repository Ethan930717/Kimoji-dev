<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use Illuminate\Http\Request;
use App\Models\Artist;
use App\Models\Torrent;


class ArtistController extends Controller
{
    public function index()
    {
        $actors = Actor::all();

        return view('actors.index', compact('actors'));
    }

    public function show($id)
    {
        $actor = Actors::findOrFail($id);
        $torrents = Torrent::where('name', 'like', '%'.$actor->name.'%')
            ->where('category_id', 3)
            ->get();

        return view('actors.show', compact('actor', 'torrents'));
    }

}
