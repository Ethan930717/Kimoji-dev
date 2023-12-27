<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'filetype' => 'required|string',
        ]);

        $filePath = 'uploads/'.$request->filename;
        $filetype = $request->filetype;

        $s3Client = Storage::disk('s3')->getClient(); // 获取 S3 客户端
        $bucket = env('AWS_BUCKET');

        $cmd = $s3Client->getCommand('PutObject', [
            'Bucket'      => $bucket,
            'Key'         => $filePath,
            'ContentType' => $filetype,
            'ACL'         => 'public-read'
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');
        $presignedUrl = (string) $request->getUri();

        return response()->json(['url' => $presignedUrl]);
    }
}
