<?php
/**
 * NOTICE OF LICENSE.
 *
 * UNIT3D Community Edition is open-sourced software licensed under the GNU Affero General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D Community Edition
 *
 * @author     HDVinnie <hdinnovations@protonmail.com>
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 */

namespace App\Observers;

use App\Models\Torrent;
use App\Http\Controllers\TelegramController;

class TorrentObserver
{
    /**
     * Handle the Torrent "created" event.
     */
    public function created(Torrent $torrent): void
    {
        cache()->put(sprintf('torrent:%s', $torrent->info_hash), $torrent);

        if ($torrent->status == 1) {
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
                default:
                    return;
            }

            $tmdbData = $this->fetchTmdbData($tmdbService);

            if ($tmdbData) {
                TelegramController::sendTorrentNotification(
                    $tmdbData['poster'],
                    $tmdbData['overview'],
                    $torrent->user->username
                );
            }
        } else {
            // 发送待审核通知
            (new TelegramController)->sendModerationNotification($torrent->name, $torrent->id);
        }
    }



    /**
     * Handle the Torrent "updated" event.
     */
    public function updated(Torrent $torrent): void
    {
        cache()->forget(sprintf('torrent:%s', $torrent->info_hash));
        cache()->put(sprintf('torrent:%s', $torrent->info_hash), $torrent);

        if ($torrent->isDirty('status') && $torrent->status == 1) {
            // 如果 status 从 0 变为 1
            TelegramController::sendMessage('数据更新，状态从 0 变为 1');
        }
    }

    private function fetchTmdbData($tmdbService)
    {
        return [
            'poster' => $tmdbService->get_poster(),
            'overview' => $tmdbService->get_overview()
        ];
    }

    /**
     * Handle the Torrent "deleted" event.
     */
    public function deleted(Torrent $torrent): void
    {
        cache()->forget(sprintf('torrent:%s', $torrent->info_hash));
    }

    /**
     * Handle the Torrent "restored" event.
     */
    public function restored(Torrent $torrent): void
    {
        cache()->put(sprintf('torrent:%s', $torrent->info_hash), $torrent);
    }
}
