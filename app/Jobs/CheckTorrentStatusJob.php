<?php

namespace App\Jobs;

use App\Models\Torrent;
use App\Http\Controllers\TelegramController;
use App\Services\Tmdb\Client\Movie;
use App\Services\Tmdb\Client\TV;
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
        Log::info("CheckTorrentStatusJob 构造函数执行，Torrent ID: " . $torrent->id);

    }

    public function handle()
    {
        // 重新获取 Torrent 实例以确保状态是最新的
        $torrent = Torrent::find($this->torrent->id);

        Log::info("CheckTorrentStatusJob 执行中，检查 Torrent 状态", ['torrentId' => $torrent->id, 'status' => $torrent->status]);

        if ($torrent && $torrent->status == 1) {
            $category = $torrent->category_id;

            switch ($category) {
                case 1:
                case 3:
                    $tmdbService = new Movie($torrent->tmdb);
                    break;
                case 2:
                case 4:
                case 5:
                    $tmdbService = new TV($torrent->tmdb);
                    break;
                case 6:
                    $fileSizeGB = round($torrent->size / 1e9, 2); // 将字节转换为 GB，并保留两位小数
                    $fileSizeText = "{$fileSizeGB} GB";
                    $telegramController = new TelegramController();
                    $telegramController->sendMusicTorrentNotification(
                        $torrent->id,
                        $torrent->name,
                        $fileSizeText
                    );
                    break;
                default:
                    return;
            }

            $tmdbData = $this->fetchTmdbData($tmdbService);

            if ($tmdbData) {
                $fileSizeGB = round($torrent->size / 1e9, 2); // 将字节转换为 GB，并保留两位小数
                $fileSizeText = "{$fileSizeGB} GB";

                $telegramController = new TelegramController();
                $telegramController->sendTorrentNotification(
                    $torrent->id,
                    $torrent->name,
                    $tmdbData['poster'],
                    $tmdbData['overview'],
                    $fileSizeText
                );
            }
        } elseif ($torrent && $torrent->status == 0) {
            Log::info("Torrent 状态为 0，发送待审核通知", ['torrentId' => $torrent->id]);

            // 执行状态为 0 的相关操作，例如发送待审核通知
            $telegramController = new TelegramController();
            $telegramController->sendModerationNotification($torrent->name, $torrent->id);
        } else {
            Log::warning("Torrent 状态不是 0 或 1，或者 Torrent 不存在", ['torrentId' => $this->torrent->id]);
        }
    }
    private function fetchTmdbData($tmdbService)
    {
        return [
            'poster' => $tmdbService->get_poster(),
            'overview' => $tmdbService->get_overview()
        ];
    }
}
