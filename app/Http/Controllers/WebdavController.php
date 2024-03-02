<?php
namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WebDAVController extends Controller
{
    public function index(Request $request)
    {
        $client = new Client([
            // WebDAV服务器基础URL
            'base_uri' => 'https://u392345.your-storagebox.de/',
            'auth' => ['u392345', 'VazkZPhSCm45DY67'], // WebDAV用户名和密码
        ]);

        try {
            $response = $client->request('GET', '1.flac'); // 指定要访问的资源路径
            $contents = $response->getBody()->getContents(); // 获取资源内容

            // 根据需要处理和返回内容
            // 例如，直接返回资源内容，或者将内容嵌入到某个视图中
            return response($contents)
                ->header('Content-Type', $response->getHeaderLine('Content-Type'));

        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to retrieve data'], 500);
        }
    }
}
