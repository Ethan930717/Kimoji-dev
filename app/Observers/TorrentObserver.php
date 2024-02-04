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
use App\Models\Artist;
use App\Models\Music;
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

        $user = $torrent->user;
        $group = $user->group;

        if ($group->is_internal === 0 && $group->is_modo === 0) {
            $telegramController = new TelegramController();
            $message = "有新的上传待审核: ".$torrent->name;
            $telegramController->sendModerationNotification($message);
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
            $category = $torrent->category_id;

            switch ($category) {
                case 1:
                    $tmdbService = new Movie($torrent->tmdb);

                    break;
                case 2:
                    $tmdbService = new TV($torrent->tmdb);

                    break;
                case 3:
                case 4:
                    $fileSizeGB = round($torrent->size / 1e9, 2); // 将字节转换为 GB，并保留两位小数
                    $fileSizeText = "{$fileSizeGB} GB";
                    $telegramController = new TelegramController();
                    $telegramController->sendMusicTorrentNotification(
                        $torrent->id,
                        $torrent->name,
                        $fileSizeText
                    );
                    // Logic for artists
                    $artistName = explode(' - ', $torrent->name, 2)[0] ?? null;
                    $artistName = trim($artistName);
                    $imageUrl = "/files/img/torrent-banner_{$torrent->id}.jpg";

                    // Check if artist already exists
                    $artist = Artist::where('name', $artistName)->first();
                    Log::info("Attempting to create artist with name: {$artistName}");

                    if (!$artist) {
                        // Artist does not exist, create new artist
                        $artist = new Artist();
                        $artist->name = $artistName;
                        $artist->image_url = $imageUrl;
                        $artist->save();
                        Log::info("Artist created with ID: {$artist->id}");
                    }

                    // Logic for music
                    $description = $torrent->description;
                    preg_match('/\[spoiler=歌曲列表\](.*?)\[\/spoiler\]/s', $description, $matches);
                    $songList = $matches[1] ?? '';

                    preg_match_all('/\d+\.\s(.*?)\s\[(\d+:\d+)\]/', $songList, $songMatches, PREG_SET_ORDER);

                    foreach ($songMatches as $songMatch) {
                        $songName = $songMatch[1] ?? '';
                        $duration = $songMatch[2] ?? '';

                        // Insert into music table
                        $music = new Music();
                        $music->song_name = $songName;
                        $music->torrent_id = $torrent->id;
                        $music->duration = $duration;
                        $music->artist_name = $artistName;
                        $music->save();
                        Log::info("Music record created for song: {$songName} with ID: {$music->id}");
                    }

                    break;
                default:
                    return;
            }

            if (isset($tmdbService)) {
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
    }
    private function fetchTmdbData($tmdbService)
    {
        if ($tmdbService instanceof Movie) {
            $data = $tmdbService->getMovie();
        } elseif ($tmdbService instanceof TV) {
            $data = $tmdbService->getTv();
        } else {
            return;
        }

        return [
            'poster'   => $data['poster'] ?? null,
            'overview' => $data['overview'] ?? null
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
