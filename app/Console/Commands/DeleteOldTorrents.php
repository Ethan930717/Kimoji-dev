<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Torrent; // 引入您的 Torrent 模型
use Carbon\Carbon;

class DeleteOldTorrents extends Command
{
    protected $signature = 'torrents:delete-old';
    protected $description = 'Delete torrents older than 3 days with status 2 or 3';

    public function handle()
    {
        $threshold = Carbon::now()->subDays(2);
        $deletedCount = Torrent::whereIn('status', [2, 3])
            ->where('created_at', '<', $threshold)
            ->delete();

        $this->info("Deleted {$deletedCount} old torrents.");
    }
}
