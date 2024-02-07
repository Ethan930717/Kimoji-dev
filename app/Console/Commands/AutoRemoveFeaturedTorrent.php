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

namespace App\Console\Commands;

use App\Models\FeaturedTorrent;
use App\Models\Torrent;
use App\Repositories\ChatRepository;
use App\Services\Unit3dAnnounce;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * @see \Tests\Unit\Console\Commands\AutoRemoveFeaturedTorrentTest
 */
class AutoRemoveFeaturedTorrent extends Command
{
    /**
     * AutoRemoveFeaturedTorrent Constructor.
     */
    public function __construct(private readonly ChatRepository $chatRepository)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:remove_featured_torrent';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Removes Featured Torrents If Expired';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        DB::beginTransaction();

        try {
            $this->addNewFeaturedTorrents();
            $this->removeExpiredFeaturedTorrents();
            DB::commit();
            $this->comment('Automated Removal and Addition of Featured Torrents Command Complete');
        } catch (Exception $e) {
            DB::rollBack();
            $this->error('An error occurred: '.$e->getMessage());
        }
    }

    /**
     * Adds new featured torrents.
     */
    private function addNewFeaturedTorrents(): void
    {
        $eligibleTorrents = Torrent::where('category_id', 3)
            ->where('seeders', '>', 2)
            ->where('seeders', '<', 10)
            ->where('featured', 0)
            ->inRandomOrder()
            ->limit(20)
            ->get();

        foreach ($eligibleTorrents as $torrent) {
            $torrent->featured = 1;

            $torrent->save();

            $featuredTorrent = new FeaturedTorrent();
            $featuredTorrent->torrent_id = $torrent->id;
            $featuredTorrent->user_id = 4; // 将 user_id 固定设置为 4
            $featuredTorrent->save();

            Log::info('Featured and FeaturedTorrent created for torrent ID: '.$torrent->id);
        }
    }

    /**
     * Removes expired featured torrents.
     */
    public function removeExpiredFeaturedTorrents(): void
    {
        $current = Carbon::now();
        $featuredTorrents = FeaturedTorrent::where('created_at', '<', $current->copy()->subDays(2)->toDateTimeString())->get();

        foreach ($featuredTorrents as $featuredTorrent) {
            // Find The Torrent
            $torrent = Torrent::where('featured', '=', 1)->find($featuredTorrent->torrent_id);

            if (isset($torrent)) {
                $torrent->free = 0;
                $torrent->doubleup = false;
                $torrent->featured = false;
                $torrent->save();

                // Auto Announce Featured Expired
                $appurl = config('app.url');

                $this->chatRepository->systemMessage(
                    sprintf('Ladies and Gents, [url=%s/torrents/%s]%s[/url] is no longer featured. :poop:', $appurl, $torrent->id, $torrent->name)
                );

                Unit3dAnnounce::addTorrent($torrent);
            }

            // Delete The Record From DB
            $featuredTorrent->delete();
        }

        $this->comment('Automated Removal Featured Torrents Command Complete');
    }
}
