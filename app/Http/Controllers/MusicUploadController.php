<?php

namespace App\Http\Controllers;

class MusicUploadController extends Controller
{
    public function index()
    {
        // 注意这里的路径改变
        return view('torrent.music-upload'); // 更新视图路径
    }
}
