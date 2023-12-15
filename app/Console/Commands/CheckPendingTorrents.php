<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Torrent;
use App\Http\Controllers\TelegramController;

class CheckPendingTorrents extends Command
{
    protected $signature = 'auto:check_pending_torrents';
    protected $description = 'Check for torrents with PENDING status and send Telegram notification';

    public function handle(): void
    {
        $pendingTorrents = Torrent::where('status', 0)->get();

        if ($pendingTorrents->isNotEmpty()) {
            $message = "以下种子处于待审核状态:\n\n";

            foreach ($pendingTorrents as $torrent) {
                $message .= "名称: {$torrent->name}, ID: {$torrent->id}\n";
            }

            // 发送合并的消息
            $telegramController = new TelegramController();
            $telegramController->sendModerationNotification($message);
        } else {
            $this->info('没有发现处于待审核状态的种子.');
        }
    }
}
