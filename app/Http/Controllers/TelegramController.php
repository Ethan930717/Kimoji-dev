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

        if (isset($update['message'])) {
            $chatId = $update['message']['chat']['id'];

            // 先检查 'text' 字段是否存在
            if (isset($update['message']['text'])) {
                $text = $update['message']['text'];

                // 记录收到的消息
                Log::info("Received message: {$text}");

                if (preg_match('/申请内测资格\s*([\w\.\-]+@\w+\.\w+)/', $text, $matches)) {
                    $email = $matches[1];

                    if ($this->isValidEmail($email)) {
                        try {
                            $this->inviteService->sendInvite($email);
                            Telegram::sendMessage([
                                'chat_id' => $chatId,
                                'text' => "内测邀请函已发送至: {$email}，注册后请勿再使用该指令，也不要向任何群外人员发送邀请，否则将取消内测资格，感谢配合"
                            ]);
                        } catch (\Exception $e) {
                            Telegram::sendMessage([
                                'chat_id' => $chatId,
                                'text' => "发送失败，失败原因：" . $e->getMessage()
                            ]);
                        }
                    } else {
                        Telegram::sendMessage([
                            'chat_id' => $chatId,
                            'text' => "提供的邮箱地址不正确，发送失败"
                        ]);
                    }
                }
            } else {
                // 如果没有 'text' 字段，记录一个不同的消息
                Log::info("Received a non-text message type");
            }
        }

        return response()->json(['status' => 'success']);
    }
}