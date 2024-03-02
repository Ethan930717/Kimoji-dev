<?php
namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WebDAVController extends Controller
{
    public function index(Request $request, $filename)
    {
        $client = new Client([
            // WebDAV服务器基础URL
            'base_uri' => 'https://u392345.your-storagebox.de/',
            'auth' => ['u392345', 'VazkZPhSCm45DY67'], // WebDAV用户名和密码
        ]);

        try {
            // 使用 filename 参数来构建请求路径
            $response = $client->request('GET', 'listen/' . $filename); // 确保路径是正确的
            $contents = $response->getBody()->getContents();

            return response($contents)
                ->header('Content-Type', $response->getHeaderLine('Content-Type'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to retrieve data: ' . $e->getMessage()], 500);
        }
    }
}
