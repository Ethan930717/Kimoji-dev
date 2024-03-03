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
                'headers' => $range ? ['Range' => $range] : [],
            ];

            // 检查文件类型，设置是否以流式传输
            if ($this->isStreamableFile($filename)) {
                $options['stream'] = true;
            }

            $file_path = $subdir . '/' . $filename;
            $response = $client->request('GET', $file_path, $options);

            if (!$this->isStreamableFile($filename)) {
                // 对于非流式文件，直接返回响应
                return response($response->getBody()->getContents())
                    ->header('Content-Type', $response->getHeaderLine('Content-Type'));
            }

            // 准备流式响应
            $stream = function () use ($response) {
                while (!$response->getBody()->eof()) {
                    echo $response->getBody()->read(2048);
                    flush();
                }
            };

            // 设置响应头
            $headers = [
                'Content-Type' => $response->getHeaderLine('Content-Type'),
                'Accept-Ranges' => 'bytes',
            ];

            if ($range && $response->hasHeader('Content-Range')) {
                $headers['Content-Range'] = $response->getHeaderLine('Content-Range');
            }

            return response()->stream($stream, 200, $headers);

        } catch (\Exception $e) {
            abort(404, '未找到该文件');
        }
    }

    /**
     * 检查文件是否应该以流式传输
     *
     * @param string $filename
     * @return bool
     */
    protected function isStreamableFile($filename)
    {
        $streamableExtensions = ['mp3', 'm4a', 'ogg', 'wav', 'flac']; // 这里可以根据需要添加更多音频文件类型
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($extension, $streamableExtensions);
    }
}
