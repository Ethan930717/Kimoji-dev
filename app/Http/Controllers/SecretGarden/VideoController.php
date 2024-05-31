<?php

namespace App\Http\Controllers\SecretGarden;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Models\Torrent;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::all();
        return view('secretgarden.video.index', compact('videos'));
    }

    public function show($id)
    {
        $video = Video::with('videos')->findOrFail($id);
        return view('secretgarden.video.show', compact('video'));
    }
}
