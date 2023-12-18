<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class EmailBlacklistUpdater
{
    public static function update(): bool|int
    {
        $url = config('email-blacklist.source');

        if ($url === null) {
            return false;
        }

        // Define parameters for the cache
        $key = config('email-blacklist.cache-key');
        $duration = Carbon::now()->addMonth();
        $timeout = 30; // 定义超时时间为30秒

        try {
            // 使用超时设置来发送请求
            $response = Http::timeout($timeout)->get($url);

            if ($response->successful()) {
                $domains = $response->json();
            } else {
                // 如果响应不成功，记录错误或采取其他措施
                Log::error("获取黑名单域名失败，响应状态码：".$response->status());

                return false;
            }
        } catch (Exception $e) {
            // 处理连接或其他异常
            Log::error("更新邮件黑名单时出错: ".$e->getMessage());

            return false;
        }

        $count = is_countable($domains) ? \count($domains) : 0;

        // Store blacklisted domains
        cache()->put($key, $domains, $duration);

        return $count;
    }
}
