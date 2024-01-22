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
            // Send private message to user (if needed)
            $pm = new PrivateMessage;
            $pm->sender_id = 1; // or admin ID
            $pm->receiver_id = $torrent->user_id; // owner of the torrent
            $pm->subject = 'Torrent Deleted';
            $pm->message = 'Your torrent ' . $torrent->name . ' has been deleted.';
            $pm->save();

            // Delete the torrent
            $torrent->delete();
        }

        $this->info('僵尸种删除完毕.');
    }
}
