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

use App\Models\Resurrection;
use App\Models\History;
use App\Models\PrivateMessage;
use App\Models\Torrent;
use App\Models\User;
use App\Repositories\ChatRepository;
use App\Services\Unit3dAnnounce;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Unit\Console\Commands\AutoGraveyardTest
 */
class AutoRewardResurrection extends Command
{
    /**
     * AutoRewardResurrection's Constructor.
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
    protected $signature = 'auto:reward_resurrection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Hands Out Rewards For Successful Resurrections';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        foreach (Resurrection::where('rewarded', '!=', 1)->oldest()->get() as $resurrection) {
            $user = User::find($resurrection->user_id);

            $torrent = Torrent::find($resurrection->torrent_id);

            if (isset($user, $torrent)) {
                $history = History::where('torrent_id', '=', $torrent->id)
                    ->where('user_id', '=', $user->id)
                    ->where('seedtime', '>=', $resurrection->seedtime)
                    ->first();
            }

            if (isset($history)) {
                $resurrection->rewarded = true;
                $resurrection->save();

                $user->fl_tokens += config('graveyard.reward');
                $user->save();

                // Bump Torrent With FL
                $torrent->bumped_at = Carbon::now();
                $torrent->free = 100;
                $torrent->fl_until = Carbon::now()->addDays(3);
                $torrent->save();

                Unit3dAnnounce::addTorrent($torrent);

                // Send Private Message
                $pm = new PrivateMessage();
                $pm->sender_id = 1;
                $pm->receiver_id = $user->id;
                $pm->subject = '成功复活死种';
                $pm->message = sprintf('十分感谢你已复活了 [url=%s/torrents/', $appurl).$torrent->id.']'.$torrent->name.'[/url] :zombie: ! 送上一枚免费令，请笑纳
                [color=red][b]这是一条系统消息，请勿回复![/b][/color]';
                $pm->save();
            }
        }

        $this->comment('Automated Reward Resurrections Command Complete');
    }
}
