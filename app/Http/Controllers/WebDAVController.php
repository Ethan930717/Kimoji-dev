<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log; // 引入 Log 门面

class WebDAVController extends Controller
{
    public function stream($path, Request $request)
    {
        $client = new Client([
            'base_uri' => Config::get('webdav.base_uri'),
            'auth' => [
                Config::get('webdav.username'),
                Config::get('webdav.password')
            ],
        ]);

        // 添加日志记录请求的路径
        Log::info("Streaming from WebDAV path: " . $path);

        try {
            // 使用 path 参数来构建请求路径
            $response = $client->request('GET', $path, [
                'stream' => true,
            ]);

            // 检查响应状态码并记录
            Log::info("Response status: " . $response->getStatusCode());

            // 准备流式响应
            $stream = function () use ($response) {
                if ($response->getBody()) {
                    while (!$response->getBody()->eof()) {
                        echo $response->getBody()->read(2048);
                        flush();
                    }
                }
            };

            // 设置必要的响应头
            $headers = [
                'Content-Type' => $response->getHeaderLine('Content-Type'),
                'Accept-Ranges' => 'bytes', // 表明服务器接受范围请求
            ];

            return response()->stream($stream, 200, $headers);

        } catch (\Exception $e) {
            // 记录错误信息
            Log::error("Failed to stream file: " . $e->getMessage());
            return abort(404, 'File not found.');
        }
    }
}
