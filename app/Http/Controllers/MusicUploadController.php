<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MusicUploadController extends Controller
{

    public function index()
    {
        // 注意这里的路径改变
        return view('torrent.music-upload'); // 更新视图路径
    }
    public function upload(Request $request)
    {
        $request->validate([
            'musicfile' => 'required|file|max:102400' // 最大 100MB
        ]);

        $file = $request->file('musicfile');
        $date = Carbon::now()->format('Y-m-d');
        $randomName = uniqid(); // 生成一个唯一的随机字符串
        $extension = $file->getClientOriginalExtension(); // 获取文件原始扩展名
        $fileName = $randomName . '.' . $extension; // 生成新的文件名
        $filePath = "uploads/{$date}/{$fileName}"; // 包括日期的文件路径

        Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');

        $fileUrl = Storage::disk('s3')->url($filePath);

        return response()->json(['url' => $fileUrl]);
    }
}
