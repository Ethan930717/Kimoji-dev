<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class MusicUploadController extends Controller
{
    public function index()
    {
        // 注意这里的路径改变
        return view('torrent.music-upload'); // 更新视图路径
    }

    public function getPresignedUrl(Request $request)
    {
        $request->validate([
            'filename' => 'required|string',
            'filetype' => 'required|string|in:audio/*', // 示例文件类型
        ]);

        $filePath = 'uploads/' . $request->filename;
        $filetype = $request->filetype;

        // 检查文件类型
        if (!in_array($filetype, ['audio/*'])) {
            return response()->json(['error' => '不支持的文件类型'], 400);
        }

        $disk = Storage::disk('s3');
        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('PutObject', [
            'Bucket' => env('AWS_BUCKET'),
            'Key' => $filePath,
            'ContentType' => $filetype,
            'ACL' => 'public-read',
        ]);

        $presignedRequest = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+20 minutes');

        $presignedUrl = (string)$presignedRequest->getUri();

        return response()->json(['url' => $presignedUrl]);
    }

}
