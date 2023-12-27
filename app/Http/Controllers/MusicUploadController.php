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
        $logMessages = [];

        try {
            $request->validate([
                'musicfile' => 'required|file|max:102400' // 最大 100MB
            ]);

            $logMessages[] = '开始文件上传';

            $file = $request->file('musicfile');
            $filePath = $file;

            $logMessages[] = "文件上传到S3的路径: {$filePath}";

            if (Storage::disk('s3')->exists($filePath)) {
                $logMessages[] = "文件 {$filePath} 已存在";
            }

            Storage::disk('s3')->put($filePath, fopen($file, 'r+'), 'public');

            if (Storage::disk('s3')->exists($filePath)) {
                $fileUrl = Storage::disk('s3')->url($filePath);
                $logMessages[] = "文件上传成功，可访问 URL: {$fileUrl}";
            } else {
                $logMessages[] = "文件 {$filePath} 在存储池中不存在，上传可能失败。";
            }

            return response()->json(['url' => $fileUrl ?? '', 'logs' => $logMessages]);
        } catch (Exception $e) {
            $logMessages[] = "文件上传失败: ".$e->getMessage();

            return response()->json(['error' => '上传失败', 'logs' => $logMessages, 'message' => $e->getMessage()], 500);
        }
    }}
