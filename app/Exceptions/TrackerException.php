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

namespace App\Exceptions;

use Throwable;
use Exception;

class TrackerException extends Exception
{
    protected const ERROR_MSG = [
        // 基础追踪器系统的错误消息
        100 => '当前Traker未开放。',

        // 关于请求的错误消息 (第1部分 HTTP 方法和路由)
        110 => '无效的请求类型：客户端请求 (:method) 不是 HTTP GET。',
        111 => '无效的操作类型 `:action`。',

        // 关于用户代理（比特流客户端）的错误消息
        120 => '无效的用户代理！',
        121 => '不允许使用浏览器、爬虫或作弊工具。',
        122 => '阻止了异常访问！',
        123 => '此客户端的用户代理太长！',
        124 => ':pattern 正则表达式错误于 :start，请联系系统管理员修复。',
        125 => '您的客户端太旧，请在 :start 之后更新。',
        126 => '客户端 :ua 不被接受！请检查我们的白名单。',
        127 => '由于：:comment，客户端 :ua 被禁用。',
        128 => '客户端 :ua 不被接受！请检查我们的黑名单。',
        129 => '无效的请求！',

        // 关于请求的错误消息 (第2部分 请求参数)
        130 => '缺失键：:attribute ！',
        131 => '无效的 :attribute！:reason',  // 一般无效，使用下面的替代。
        132 => '无效的 :attribute！:attribute 的长度必须是 :rule',
        133 => '无效的 :attribute！:attribute 不是 :rule 字节长',
        134 => '无效的 :attribute！:attribute 必须是大于或等于0的数字',
        135 => '非法端口 :port。端口应在6881-64999之间',
        136 => '不支持的事件类型 :event。',
        137 => '在事件类型 :event 下非法端口0。',
        138 => '你已达到速率限制。你只能从最多 :limit 个位置同时上传/下载一个种子。',

        // 关于用户账户的错误消息
        140 => 'Passkey 不存在！请重新下载',
        141 => '您的账户未启用！（当前状态 `:status`）',
        142 => '您的下载权限已被禁用！（请阅读规则）',

        // 关于种子的错误消息
        150 => '此种子未在Trakcer注册。',
        151 => '你没有权限访问 :status 状态的种子。',
        152 => '被声明为完成的种子未找到记录。',

        // 关于下载会话的错误消息
        160 => '你不能从超过 :count 个地点上传同一个种子。',
        161 => '你已经在下载相同的种子，只能从一个地点下载！',
        162 => '你最后一次通告是 :elapsed 秒前。请注意最小间隔。',
        163 => '你的Ratio太低！你需要等待 :sec 秒才能开始。',
        164 => '已达下载数上限！你最多同时下载 :max 个种子',

        // 反作弊系统的错误消息
        170 => "我们认为你正在尝试作弊。你的账户已被禁用。",

        // 测试消息
        998 => '内部服务器错误 :msg',
        999 => ':test',
    ];

    /**
     * TrackerException constructor.
     */
    public function __construct(int $code = 999, array $replace = null, Throwable $throwable = null)
    {
        $message = self::ERROR_MSG[$code];

        if ($replace) {
            foreach ($replace as $key => $value) {
                $message = str_replace($key, $value, $message);
            }
        }

        parent::__construct($message, $code, $throwable);
    }
}
