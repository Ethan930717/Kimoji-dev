<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;


class WebDAVController extends Controller
{
    public function stream($subdir, $filename, Request $request)
    {
        $client = new Client([
            'base_uri' => Config::get('webdav.base_uri'),
            'auth' => [
                Config::get('webdav.username'),
                Config::get('webdav.password')
            ],
        ]);

        try {
            $range = $request->header('Range');
            $options = [
                'stream' => true,
                'headers' => [],
            ];
            if ($range) {
                $options['headers']['Range'] = $range;
            }

            // 使用 subdir 和 filename 参数来构建请求路径
            $file_path = $subdir . '/' . $filename;
            $response = $client->request('GET', $file_path, $options);

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

            // 如果处理了范围请求，应设置正确的Content-Range
            if ($range && $response->hasHeader('Content-Range')) {
                $headers['Content-Range'] = $response->getHeaderLine('Content-Range');
            }

            return response()->stream($stream, 200, $headers);

        } catch (\Exception $e) {
            return abort(404, 'File not found.');
        }
    }
}