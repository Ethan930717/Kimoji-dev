<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\TorrentController;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        $userId = 3;
        $user = User::findOrFail($userId);
        Auth::setUser($user);

        // 创建一个请求实例，并加入用户信息
        $request = new \Illuminate\Http\Request();
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $idsToDelete = file('/home/script/kimoji/movie.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $message = 'Delete All Video Resources'; // 这里设置一个通用的删除信息
        $request->merge(['message' => $message]);



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
