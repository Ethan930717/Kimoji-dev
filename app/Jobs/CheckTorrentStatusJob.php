<?php

namespace App\Jobs;

use App\Models\Torrent;
use App\Http\Controllers\TelegramController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckTorrentStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $torrent;

    public function __construct(Torrent $torrent)
    {
        $this->torrent = $torrent;
    }

    public function handle()
    {
        // 重新从数据库获取最新的 Torrent 状态
        $torrent = Torrent::find($this->torrent->id);

        if ($torrent && $torrent->status == 0) {
            // 如果 10 秒后状态仍然是 0，则发送待审核通知
            $telegramController = new TelegramController();
            $telegramController->sendModerationNotification($torrent->name, $torrent->id);
        }
    }
}
