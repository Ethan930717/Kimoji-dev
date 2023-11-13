<?php

namespace App\Http\Controllers;

use App\Services\InviteService;
use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;
use Illuminate\Support\Facades\Validator;

class TelegramController extends Controller
{
    protected $inviteService;

    public function __construct(InviteService $inviteService)
    {
        $this->inviteService = $inviteService;
    }

    public function handleWebhook(Request $request)
    {
        $update = Telegram::commandsHandler(true);

        if (isset($update['message'])) {
            $chatId = $update['message']['chat']['id'];
            $text = $update['message']['text'];

            if ($this->isValidEmail($text)) {
                try {
                    $this->inviteService->sendInvite($text);
                    Telegram::sendMessage([
                        'chat_id' => $chatId,
                        'text' => "内测邀请函已发送，欢迎您加入KIMOJI"
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

        return response()->json(['status' => 'success']);
    }

    private function isValidEmail($email)
    {
        return Validator::make(['email' => $email], [
            'email' => 'required|email',
        ])->passes();
    }
}
