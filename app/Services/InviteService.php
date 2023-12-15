<?php

namespace App\Services;

use App\Models\Invite;
use App\Mail\InviteUser;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;
use Exception;

class InviteService
{
    public function sendInvite($email)
    {
        $userId = 3;  // 默认 user_id
        $customMessage = "内测邀请";  // 默认邀请消息

        try {
            $invite = Invite::create([
                'user_id'    => $userId,
                'email'      => $email,
                'code'       => Uuid::uuid4()->toString(),
                'expires_on' => now()->addDays(config('other.invite_expire')),
                'custom'     => $customMessage,
            ]);

            Mail::to($email)->send(new InviteUser($invite));
        } catch (Exception $e) {
            throw $e;
        }

        return $invite;
    }
}
