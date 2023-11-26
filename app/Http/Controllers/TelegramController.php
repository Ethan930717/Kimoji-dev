<?php

namespace App\Http\Controllers;

use App\Services\InviteService;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    protected $inviteService;

    public function __construct(InviteService $inviteService)
    {
        $this->inviteService = $inviteService;
    }

    public function isValidEmail($email)
    {
        return Validator::make(['email' => $email], [
            'email' => 'required|email',
        ])->passes();
    }

    public function handleWebhook(Request $request)
    {
        $update = Telegram::commandsHandler(true);

        // 记录整个更新的详细信息
        Log::info("Received update from Telegram", ['update' => $update]);

        if (isset($update['message'])) {
            $chatId = $update['message']['chat']['id'];
            $senderId = $update['message']['from']['id']; // 发送者的 ID
            $chatType = $update['message']['chat']['type']; // 聊天类型，如群组或私聊

            // 记录更多的消息详细信息
            Log::info("Message details", [
                'chat_id' => $chatId,
                'sender_id' => $senderId,
                'chat_type' => $chatType
            ]);
            // 先检查 'text' 字段是否存在
            if (isset($update['message']['text'])) {
                $text = $update['message']['text'];
                Log::info("Received message: {$text}");

                //机器人帮助指南
                if (strpos($text, '阿K') === 0) {
                    $this->handleHelpCommand($chatId);
                    return response()->json(['status' => 'success']);
                }
                // 检查更新中是否有新成员加入
                if (isset($update['message']['new_chat_members'])) {
                    $newMembers = $update['message']['new_chat_members'];
                    foreach ($newMembers as $member) {
                        $welcomeMessage = "欢迎 " . $member['first_name'] . " 来到Kimoji ";
                        Telegram::sendMessage([
                            'chat_id' => $chatId,
                            'text' => $welcomeMessage
                        ]);
                    }
                    return response()->json(['status' => 'success']);
                }
                //自动发药，申请内测资格
//                if (preg_match('/申请内测资格\s*([\w\.\-]+@\w+\.\w+)/', $text, $matches)) {
//                    $email = $matches[1];
//
//                    if ($this->isValidEmail($email)) {
//                        try {
//                            $this->inviteService->sendInvite($email);
//                            Telegram::sendMessage([
//                                'chat_id' => $chatId,
//                                'text' => "内测邀请函已发送至: {$email}，注册后请勿再使用该指令，也不要向任何群外人员发送邀请，否则将取消内测资格，感谢配合"
//                            ]);
//                        } catch (\Exception $e) {
//                            Telegram::sendMessage([
//                                'chat_id' => $chatId,
//                                'text' => "发送失败，失败原因：" . $e->getMessage()
//                            ]);
//                        }
//                    } else {
//                        Telegram::sendMessage([
//                            'chat_id' => $chatId,
//                            'text' => "提供的邮箱地址不正确，发送失败"
//                        ]);
//                    }
//                }
            } else {
                // 如果没有 'text' 字段，记录一个不同的消息
                Log::info("Received a non-text message type");
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function handleHelpCommand($chatId)
    {
        $helpMessage = "阿K当前支持的指令:\n";
        $helpMessage .= "阿K - 显示帮助信息\n";
        #$helpMessage .= "申请内测资格 [邮箱] - 申请加入内测";

        Telegram::sendMessage([
            'chat_id' => $chatId,
            'text' => $helpMessage
        ]);
    }
    public function sendTorrentNotification($poster, $overview, $uploader)
    {
        try {
            $chatId = '-4047467856'; // 替换为您的 Telegram 群组 ID
            $message = "{$uploader} 上传了新资源：\n\n" . $overview; // 使用上传者的用户名
            $photo = $poster; // 海报图片 URL

            // 记录发送前的日志
            Log::info("Sending torrent notification to Telegram", [
                'chat_id' => $chatId,
                'message' => $message,
                'photo' => $photo
            ]);

            $response = Telegram::sendPhoto([
                'chat_id' => $chatId,
                'photo' => $photo,
                'caption' => $message
            ]);

            // 记录发送后的日志
            Log::info("Torrent notification sent", ['response' => $response]);

        } catch (\Exception $e) {
            // 记录异常
            Log::error("Error sending torrent notification to Telegram", ['error' => $e->getMessage()]);
        }
    }
}

