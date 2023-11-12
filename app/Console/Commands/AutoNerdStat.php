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

use App\Models\Ban;
use App\Models\Peer;
use App\Models\Torrent;
use App\Models\User;
use App\Models\Warning;
use App\Repositories\ChatRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Exception;

/**
 * @see \Tests\Todo\Unit\Console\Commands\AutoNerdStatTest
 */
class AutoNerdStat extends Command
{
    /**
     * AutoNerdStat Constructor.
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
    protected $signature = 'auto:nerdstat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Posts Daily Nerd Stat To Shoutbox';

    /**
     * Execute the console command.
     *
     * @throws Exception
     */
    public function handle(): void
    {
        if (config('chat.nerd_bot')) {
            // Site Birthday
            $bday = config('other.birthdate');

            // Logins Count Last 24hours
            $logins = User::whereNotNull('last_login')->where('last_login', '>', Carbon::now()->subDay())->count();

            // Torrents Uploaded Count Last 24hours
            $uploads = Torrent::where('created_at', '>', Carbon::now()->subDay())->count();

            // New Users Count Last 24hours
            $users = User::where('created_at', '>', Carbon::now()->subDay())->count();

            // Top Banker
            $banker = User::latest('seedbonus')->first();

            // Most Snatched Torrent
            $snatched = Torrent::latest('times_completed')->first();

            // Most Seeded Torrent
            $seeded = Torrent::latest('seeders')->first();

            // Most Leeched Torrent
            $leeched = Torrent::latest('leechers')->first();

            // 25% FL Torrents
            $fl25 = Torrent::where('free', '=', 25)->count();

            // 50% FL Torrents
            $fl50 = Torrent::where('free', '=', 50)->count();

            // 75% FL Torrents
            $fl75 = Torrent::where('free', '=', 75)->count();

            // 100% FL Torrents
            $fl100 = Torrent::where('free', '=', 100)->count();

            // DU Torrents
            $du = Torrent::where('doubleup', '=', 1)->count();

            // Peers Count
            $peers = Peer::where('active', '=', 1)->count();

            // New User Bans Count Last 24hours
            $bans = Ban::whereNull('unban_reason')->whereNull('removed_at')->where('created_at', '>', Carbon::now()->subDay())->count();

            // Hit and Run Warning Issued In Last 24hours
            $warnings = Warning::where('created_at', '>', Carbon::now()->subDay())->count();

            // URL Helpers
            $bankerUrl = href_profile($banker);
            $seededUrl = href_torrent($seeded);
            $leechedUrl = href_torrent($leeched);
            $snatchedUrl = href_torrent($snatched);

            // Select A Random Nerd Stat
            $statArray = [
                sprintf('在过去的24小时内，共有 [color=#e54736][b]%s[/b][/color] 位独立用户登录了 ', $logins).config('other.title').'！',
                sprintf('在过去的24小时内，共有 [color=#e54736][b]%s[/b][/color] 个种子被上传到 ', $uploads).config('other.title').'！',
                sprintf('在过去的24小时内，共有 [color=#e54736][b]%s[/b][/color] 位用户注册到 ', $users).config('other.title').'！',
                sprintf('目前共有 [color=#e54736][b]%s[/b][/color] 个25%% 免费种子在 ', $fl25).config('other.title').'！',
                sprintf('目前共有 [color=#e54736][b]%s[/b][/color] 个50%% 免费种子在 ', $fl50).config('other.title').'！',
                sprintf('目前共有 [color=#e54736][b]%s[/b][/color] 个75%% 免费种子在 ', $fl75).config('other.title').'！',
                sprintf('目前共有 [color=#e54736][b]%s[/b][/color] 个100%% 免费种子在 ', $fl100).config('other.title').'！',
                sprintf('目前共有 [color=#e54736][b]%s[/b][/color] 个双倍上传种子在 ', $du).config('other.title').'！',
                sprintf('目前 [url=%s]%s[/url] 是在 ', $seededUrl, $seeded->name).config('other.title').' 上种子做种最好的！',
                sprintf('目前 [url=%s]%s[/url] 是在 ', $leechedUrl, $leeched->name).config('other.title').' 上下载最多的种子！',
                sprintf('目前 [url=%s]%s[/url] 是在 ', $snatchedUrl, $snatched->name).config('other.title').' 上被抓取最多的种子！',
                sprintf('目前 [url=%s]%s[/url] 是在 ', $bankerUrl, $banker->username).config('other.title').' 上最高BON持有者！',
                sprintf('目前共有 [color=#e54736][b]%s[/b][/color] 个用户在 ', $peers).config('other.title').'！',
                sprintf('在过去的24小时内，共有 [color=#e54736][b]%s[/b][/color] 位用户从 ', $bans).config('other.title').' 被禁止！',
                sprintf('在过去的24小时内，共有 [color=#e54736][b]%s[/b][/color] 个H&R警告在 ', $warnings).config('other.title').' 被发布！',
                config('other.title').sprintf(' 的生日是 [b]%s[/b]！', $bday),
                config('other.title').' 是王者！',
            ];

            $selected = random_int(0, \count($statArray) - 1);

            // Auto Shout Nerd Stat
            $this->chatRepository->systemMessage($statArray[$selected], 2);
        }

        $this->comment('Automated Nerd Stat Command Complete');
    }
}
