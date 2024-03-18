<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TorrentController;
use Illuminate\Http\Request;

class BulkDeleteTorrents extends Command
{
    protected $signature = 'torrents:bulk-delete';
    protected $description = 'Bulk delete torrents';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(TorrentController $torrentController)
    {
        $idsToDelete = file('/home/script/kimoji/movie.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $message = 'Delete All Video Resources'; // 这里设置一个通用的删除信息

        $request = Request::create('/', 'DELETE', [
            'message' => $message,
        ]);

        foreach ($idsToDelete as $id) {
            try {
                $torrentController->destroy($request, $id);
                $this->info("Torrent with ID {$id} has been deleted.");
            } catch (\Exception $e) {
                $this->error("Failed to delete torrent with ID {$id}: {$e->getMessage()}");
            }
        }

        $this->info('Bulk delete operation completed.');
    }
}
