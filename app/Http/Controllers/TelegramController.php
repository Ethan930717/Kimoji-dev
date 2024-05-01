<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;
use Exception;

class TelegramController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $update = Telegram::commandsHandler(true);

        if (isset($update['message'])) {
            $chatId = $update['message']['chat']['id'];
            $senderId = $update['message']['from']['id']; // 发送者的 ID
            $chatType = $update['message']['chat']['type']; // 聊天类型，如群组或私聊

            // 先检查 'text' 字段是否存在
            if (isset($update['message']['text'])) {
                $text = $update['message']['text'];
                Log::info("Received message: {$text}");

                // 检查更新中是否有新成员加入
                if (isset($update['message']['new_chat_members'])) {
                    $newMembers = $update['message']['new_chat_members'];

                    foreach ($newMembers as $member) {
                        $welcomeMessage = "欢迎 ".$member['first_name']." 来到Kimoji ";
                        Telegram::sendMessage([
                            'chat_id' => $chatId,
                            'text'    => $welcomeMessage
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

    public function sendTorrentNotification($id, $name, $poster, $overview, $size): void
    {
        try {
            // 检查名称中是否包含 "KIMOJI"
            $prefixMessage = str_contains($name, "KIMOJI") ? "勤劳的阿K发官种啦" : "来自阿K的新种播报：";

            // 构建消息文本
            $message = $prefixMessage.PHP_EOL.PHP_EOL.
                $name.PHP_EOL.PHP_EOL.
                "体积:".$size.PHP_EOL.PHP_EOL.
                "传送门:"."https://kimoji.club/torrents/".$id;

            $photo = $poster; // 海报图片 URL
            $chatId = "-1002109790916"; // Telegram 聊天 ID 或群组 ID

            // 发送带图片的消息
            $response = Telegram::sendPhoto([
                'chat_id' => $chatId,
                'photo'   => InputFile::create($photo, basename($photo)),
                'caption' => $message
            ]);
        } catch (Exception $e) {
            // 记录异常
            Log::error("Error sending torrent notification to Telegram", ['error' => $e->getMessage()]);
        }
    }

    public function sendMusicTorrentNotification($id, $name, $distributor, $region, $size, $songList): void
    {
        try {
            $prefixMessage = str_contains($name, "KIMOJI") ? "From DJ.K：" : "New Album Attention：";
 
            // 构建消息文本
            $message = $prefixMessage.PHP_EOL.PHP_EOL.
                $name.PHP_EOL.
                "Genre:".$distributor." => ".$region.PHP_EOL.
                $songList.PHP_EOL.
                "Size:".$size.PHP_EOL.
                "URL:"."https://kimoji.club/torrents/".$id;

            $photo = 'https://kimoji.club/files/img/torrent-cover_'.$id.'.jpg'; // 海报图片 URL
            $chatId = "-1002109790916"; // Telegram 聊天 ID 或群组 ID

            // 发送带图片的消息
            $response = Telegram::sendPhoto([
                'chat_id' => $chatId,
                'photo'   => InputFile::create($photo, basename($photo)),
                'caption' => $message
            ]);
        } catch (Exception $e) {
            // 记录异常
            Log::error("音乐新种TG推送错误", ['error' => $e->getMessage()]);
        }
    }

    public function sendTicketNotification($id, $category_id, $subject): void
    {
        try {
            // 根据 category_id 转换为实际的工单类型
            $categories = [
                1  => '账号',
                2  => '申诉',
                3  => '论坛',
                4  => '求种',
                5  => '字幕',
                6  => '种子',
                7  => '影视库',
                8  => '技术相关',
                9  => '播单',
                10 => '上报Bug',
                11 => '其他',
            ];
            $categoryName = $categories[$category_id] ?? '未知';

            // 构建消息文本
            $message = "收到新的工单，请及时处理".PHP_EOL.PHP_EOL.
                "工单类型: ".$categoryName.PHP_EOL.
                "工单主题: ".$subject.PHP_EOL.PHP_EOL.
                "传送门: https://kimoji.club/tickets/".$id;

            $chatId = "-1002007902628"; // Telegram 聊天 ID 或群组 ID

            // 发送文本消息
            $response = Telegram::sendMessage([
                'chat_id' => $chatId,
                'text'    => $message,
            ]);
        } catch (Exception $e) {
            // 记录异常
            Log::error("Error sending ticket notification to Telegram", ['error' => $e->getMessage()]);
        }
    }

    public function sendModerationNotification($message): void
    {
        try {
            $chatId = "-1002007902628"; // 您的 Telegram 群组ID
            $fullMessage = $message.PHP_EOL.PHP_EOL.
                "请尽快处理:"."https://kimoji.club/dashboard/moderation";

            // 发送消息
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text'    => $fullMessage
            ]);
        } catch (Exception $e) {
            Log::error("发送待审通知异常", ['error' => $e->getMessage()]);
        }
    }

    public function sendNewApplicationNotification($applicationDetails, $images = []): void
    {
        try {
            $chatId = "-1002007902628"; // 替换为你的 Telegram 群组ID
            $message = "收到了新的入站申请：\n\n".$applicationDetails;

            // 发送文本消息
            Telegram::sendMessage([
                'chat_id' => $chatId,
                'text'    => $message
            ]);

            // 如果提供了图片链接数组，发送图片
            if (!empty($images)) {
                foreach ($images as $image) {
                    Telegram::sendPhoto([
                        'chat_id' => $chatId,
                        'photo'   => InputFile::create($image)
                    ]);
                }
            }
        } catch (Exception $e) {
            Log::error("Error sending new application notification to Telegram", ['error' => $e->getMessage()]);
        }
    }
}
