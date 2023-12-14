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
use App\Jobs\CheckTorrentStatusJob;
use App\Models\Torrent;
use App\Http\Controllers\TelegramController;
use App\Services\Tmdb\Client\Movie;
use App\Services\Tmdb\Client\TV;
use Illuminate\Support\Facades\Log;


class TorrentObserver
{
    /**
     * Handle the Torrent "created" event.
     */
    public function created(Torrent $torrent): void
    {
        cache()->put(sprintf('torrent:%s', $torrent->info_hash), $torrent);

    }

    /**
     * Handle the Torrent "updated" event.
     */
    public function updated(Torrent $torrent): void
    {
        cache()->forget(sprintf('torrent:%s', $torrent->info_hash));
        cache()->put(sprintf('torrent:%s', $torrent->info_hash), $torrent);

        if ($torrent->isDirty('status') && $torrent->status == 1) {
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
