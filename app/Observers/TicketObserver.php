<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Http\Controllers\TelegramController;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
        // 在这里，你可以添加代码以向 Telegram 群组发送消息
        $telegramController = new TelegramController();
        $telegramController->sendTicketNotification($ticket->id, $ticket->category_id, $ticket->subject);
    }

    // 你可以根据需要添加其他方法，比如 updated, deleted 等
}
