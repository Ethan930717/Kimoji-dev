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

            if (preg_match('/申请内测资格[\w\.\-]+@\w+\.\w+/', $text, $matches)) {
                $email = str_replace('申请内测资格', '', $matches[0]);

                if ($this->isValidEmail($email)) {
                    try {
                        $this->inviteService->sendInvite($email);
                        Telegram::sendMessage([
                            'chat_id' => $chatId,
                            'text' => "内测邀请函已发送至: {$email}，注册成功后请勿重复使用该指令，也不要为除本群之外的其他人注册账号，否则将取消内测资格"
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
        }

        return response()->json(['status' => 'success']);
    }}
