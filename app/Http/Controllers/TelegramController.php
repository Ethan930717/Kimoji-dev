<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

class TelegramController extends Controller
{

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
            $message = "$uploader 上传了新资源：\n\n" . $overview; // 使用上传者的用户名
            $photo = $poster; // 海报图片 URL
            $chatId = "-4047467856";
            // 记录发送前的日志
            Log::info("Sending torrent notification to Telegram", [
                'chat_id' => $chatId,
                'message' => $message,
                'photo' => $photo
            ]);

            $response = Telegram::sendPhoto([
                'chat_id' => $chatId,
                'photo' => InputFile::create($photo, basename($photo)),
                'caption' => $message
            ]);

            // 记录发送后的日志
            Log::info("Torrent notification sent", ['response' => $response]);

        } catch (\Exception $e) {
            // 记录异常
            Log::error("Error sending torrent notification to Telegram", ['error' => $e->getMessage()]);
        }
    }
    public function sendModerationNotification($torrentName, $torrentId)
    {
        try {
            $chatId = "-4047467856"; // 您的 Telegram 群组ID
            $message = "有新的待审核种子：{$torrentName} (ID: {$torrentId})";

            // 发送消息
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $message
            ]);

            Log::info("Sent moderation notification to Telegram", ['chat_id' => $chatId, 'message' => $message]);
        } catch (\Exception $e) {
            Log::error("Error sending moderation notification to Telegram", ['error' => $e->getMessage()]);
        }
    }

    public function sendNewApplicationNotification($applicationDetails)
    {
        try {
            $chatId = "-4047467856"; // 替换为你的 Telegram 群组ID
            $message = "收到了新的入站申请：\n\n" . $applicationDetails;

            // 发送消息
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $message
            ]);

            Log::info("Sent new application notification to Telegram", ['chat_id' => $chatId, 'message' => $message]);
        } catch (\Exception $e) {
            Log::error("Error sending new application notification to Telegram", ['error' => $e->getMessage()]);
        }
    }

}

