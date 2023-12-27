<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
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

            Log::info('开始文件上传');

            $file = $request->file('musicfile');
            $date = Carbon::now()->format('Y-m-d');
            $randomName = uniqid();
            $extension = $file->getClientOriginalExtension();
            $fileName = $randomName.'.'.$extension;
            $filePath = "uploads/{$date}/{$fileName}";

            Log::info("文件上传到S3: {$filePath}");

            Storage::disk('s3')->put($filePath, file_get_contents($file), 'public');

            $fileUrl = Storage::disk('s3')->url($filePath);

            Log::info("文件上传成功: {$fileUrl}");

            return response()->json(['url' => $fileUrl]);
        } catch (Exception $e) {
            Log::error("文件上传失败: ".$e->getMessage());

            return response()->json(['error' => '上传失败'], 500);
        }
    }
}
