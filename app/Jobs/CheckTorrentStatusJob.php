<?php

namespace App\Jobs;

use App\Models\Torrent;
use App\Http\Controllers\TelegramController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckTorrentStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $torrent;

    public function __construct(Torrent $torrent)
    {
        $this->torrent = $torrent;
        Log::info("CheckTorrentStatusJob 构造函数调用", ['torrent_id' => $torrent->id]);
    }

    public function handle()
    {
        Log::info("开始处理 CheckTorrentStatusJob", ['torrent_id' => $this->torrent->id]);

        // 重新从数据库获取最新的 Torrent 状态
        $torrent = Torrent::find($this->torrent->id);

        if ($torrent) {
            Log::info("Torrent 在数据库中找到", ['torrent_id' => $torrent->id, 'status' => $torrent->status]);

            if ($torrent->status == 0) {
                // 如果 10 秒后状态仍然是 0，则发送待审核通知
                Log::info("Torrent 状态仍为 0, 发送待审核通知", ['torrent_id' => $torrent->id]);

                $telegramController = new TelegramController();
                $telegramController->sendModerationNotification($torrent->name, $torrent->id);
            } else {
                Log::info("Torrent 状态已更改，不发送待审核通知", ['torrent_id' => $torrent->id, 'status' => $torrent->status]);
            }
        } else {
            Log::error("在数据库中找不到指定的 Torrent", ['torrent_id' => $this->torrent->id]);
        }
    }
}
