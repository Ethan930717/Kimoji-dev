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
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $torrentId;

    public function getTorrentId()
    {
        return $this->torrentId;
    }
    public function __construct($torrentId)
    {
        $this->torrentId = $torrentId;
        Log::info("CheckTorrentStatusJob 构造函数执行，Torrent ID: ".$this->torrentId);
    }

    public function handle(): void
    {
        $torrent = Torrent::find($this->torrentId);

        if (!$torrent) {
            Log::error("该种子不存在或已被删除", ['torrentId' => $this->torrentId]);

            return;
        }

        Log::info("CheckTorrentStatusJob 执行中，检查 Torrent 状态", ['torrentId' => $torrent->id, 'status' => $torrent->status]);

        if ($torrent->status == 0) {
            Log::info("Torrent 状态为 0，发送待审核通知", ['torrentId' => $torrent->id]);
            $telegramController = new TelegramController();
            $telegramController->sendModerationNotification($torrent->name, $torrent->id);
        }
    }
}
