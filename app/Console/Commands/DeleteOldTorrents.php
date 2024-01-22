<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Torrent;
use Carbon\Carbon;

class DeleteOldTorrents extends Command
{
    protected $signature = 'torrents:delete-old';
    protected $description = 'Deletes torrents older than 21 days with zero seeders';

    public function handle(): void
    {
        $oldTorrents = Torrent::where('seeders_zero_at', '<', Carbon::now()->subDays(21))
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
