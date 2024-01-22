<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Torrent;
use App\Models\PrivateMessage;
use Carbon\Carbon;

class DeleteOldTorrents extends Command
{
    protected $signature = 'torrents:delete-old';
    protected $description = 'Deletes torrents older than 14 days with zero seeders';

    public function handle()
    {
        $oldTorrents = Torrent::where('updated_at', '<', Carbon::now()->subDays(21))
            ->where('seeders', 0)
            ->get();

        if ($oldTorrents->isEmpty()) {
            $this->info('No old torrents to delete.');
            return;
        }

        foreach ($oldTorrents as $torrent) {

            // Delete the torrent
            $torrent->delete();
        }

        $this->info('僵尸种删除完毕.');
    }
}
