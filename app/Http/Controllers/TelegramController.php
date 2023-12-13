<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;
use App\Models\Torrent;
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


    public function notifyNewTorrent(Torrent $torrent)
    {
        Log::info("notifyNewTorrent method called for torrent: {$torrent->id}");

        // 获取电影或电视剧的信息
        $meta = $torrent->category->movie_meta ? Movie::find($torrent->tmdb) : Tv::find($torrent->tmdb);

        if ($meta) {
            $poster = $meta->poster;
            $overview = $meta->overview;
            $uploader = $torrent->user->username;

            // 使用 sendTorrentNotification 方法发送通知
            $this->sendTorrentNotification($poster, $overview, $uploader);
        }
    }

    public function sendTorrentNotification($id, $name,$poster, $overview, $size)
    {
        try {
            // 构建消息文本
            $message = "来自阿K的新种通知：" . PHP_EOL . PHP_EOL .
                $name . PHP_EOL . PHP_EOL .
                "影片简介:" . $overview . PHP_EOL . PHP_EOL .
                "体积:" . $size . PHP_EOL . PHP_EOL .
                "传送门:" . "https://kimoji.club/torrents/" . $id;

            $photo = $poster; // 海报图片 URL
            $chatId = "-4047467856"; // Telegram 聊天 ID 或群组 ID

            // 记录发送前的日志
            Log::info("Sending torrent notification to Telegram", [
                'chat_id' => $chatId,
                'message' => $message,
                'photo' => $photo
            ]);

            // 发送带图片的消息
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

    public function sendMusicTorrentNotification($id, $name , $size)
    {
        try {
            // 构建消息文本
            $message = "DJ阿K的新音乐播送通知：" . PHP_EOL . PHP_EOL .
                $name . PHP_EOL . PHP_EOL .
                "体积:" . $size . PHP_EOL . PHP_EOL .
                "传送门:" . "https://kimoji.club/torrents/" . $id;

            $photo = 'https://kimoji.club/files/img/torrent-cover_' . $id .'.jpg'; // 海报图片 URL
            $chatId = "-4047467856"; // Telegram 聊天 ID 或群组 ID

            // 记录发送前的日志
            Log::info("Sending torrent notification to Telegram", [
                'chat_id' => $chatId,
                'message' => $message,
                'photo' => $photo
            ]);

            // 发送带图片的消息
            $response = Telegram::sendPhoto([
                'chat_id' => $chatId,
                'photo' => InputFile::create($photo, basename($photo)),
                'caption' => $message
            ]);

            // 记录发送后的日志
            Log::info("新种推送", ['response' => $response]);

        } catch (\Exception $e) {
            // 记录异常
            Log::error("音乐新种TG推送错误", ['error' => $e->getMessage()]);
        }
    }

    public function sendModerationNotification($torrentName, $torrentId)
    {
        try {
            $chatId = "-4083300344"; // 您的 Telegram 群组ID
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

    public function sendNewApplicationNotification($applicationDetails, $images = [])
    {
        try {
            $chatId = "-4083300344"; // 替换为你的 Telegram 群组ID
            $message = "收到了新的入站申请：\n\n" . $applicationDetails;

            // 发送文本消息
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text' => $message
            ]);

            // 如果提供了图片链接数组，发送图片
            if (!empty($images)) {
                foreach ($images as $image) {
                    Telegram::sendPhoto([
                        'chat_id' => $chatId,
                        'photo' => InputFile::create($image)
                    ]);
                }
            }

            Log::info("Sent new application notification to Telegram", ['chat_id' => $chatId, 'message' => $message]);
        } catch (\Exception $e) {
            Log::error("Error sending new application notification to Telegram", ['error' => $e->getMessage()]);
        }
    }
}

