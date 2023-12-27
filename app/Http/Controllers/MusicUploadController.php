<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class MusicUploadController extends Controller
{
    public function index()
    {
        // 注意这里的路径改变
        return view('torrent.music-upload'); // 更新视图路径
    }

    public function upload(Request $request)
    {
        try {
            $request->validate([
                'musicfile' => 'required|file|max:102400' // 最大 100MB
            ]);

            Log::info('开始文件上传', ['user' => $request->user()]);

            $file = $request->file('musicfile');
            $filePath = $file;

            Log::info("文件上传到S3的路径: {$filePath}");

            // 检查文件是否已经存在
            if (Storage::disk('s3')->exists($filePath)) {
                Log::warning("文件 {$filePath} 已存在");
            }

            Storage::disk('s3')->put($filePath, fopen($file, 'r+'), 'public');

            if (Storage::disk('s3')->exists($filePath)) {
                $fileUrl = Storage::disk('s3')->url($filePath);
                Log::info("文件上传成功，可访问 URL: {$fileUrl}");
            } else {
                Log::error("文件 {$filePath} 在存储池中不存在，上传可能失败。");
            }

            return response()->json(['url' => $fileUrl ?? '']);
        } catch (Exception $e) {
            Log::error("文件上传失败: ".$e->getMessage(), [
                'file' => $request->file('musicfile')->getClientOriginalName(),
                'size' => $request->file('musicfile')->getSize(),
                'mime' => $request->file('musicfile')->getMimeType(),
            ]);

            return response()->json(['error' => '上传失败', 'message' => $e->getMessage()], 500);
        }
    }
}
