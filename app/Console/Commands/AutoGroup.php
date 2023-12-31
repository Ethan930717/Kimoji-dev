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

use App\Enums\UserGroups;
use App\Helpers\ByteUnits;
use App\Models\Group;
use App\Models\History;
use App\Models\User;
use App\Models\Peer;
use App\Services\Unit3dAnnounce;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

/**
 * @see \Tests\Unit\Console\Commands\AutoGroupTest
 */
class AutoGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:group';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically Change A Users Group Class If Requirements Met';

    /**
     * Execute the console command.
     */
    public function handle(ByteUnits $byteUnits): void
    {
        // Temp Hard Coding of Immune Groups (Config Files To Come)
        $current = Carbon::now();
        $groups = Group::where('autogroup', '=', 1)->pluck('id');

        foreach (User::whereIntegerInRaw('group_id', $groups)->get() as $user) {
            $hiscount = History::where('user_id', '=', $user->id)->count();

            // Temp Hard Coding of Group Requirements (Config Files To Come) (Upload in Bytes!) (Seedtime in Seconds!)
            $excludedGroups = [UserGroups::INTERNAL->value, UserGroups::KEEPER->value];

            // Leech ratio dropped below sites minimum
            if ($user->ratio < config('other.ratio') &&
                $user->group_id != UserGroups::LEECH->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = UserGroups::LEECH->value;
                $user->can_request = false;
                $user->can_invite = false;
                $user->can_download = false;
                $user->save();
            }

            // User >= 0 and ratio above sites minimum
            if ($user->uploaded >= 0 &&
                $user->ratio >= config('other.ratio') &&
                $user->group_id != UserGroups::USER->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = UserGroups::USER->value;
                $user->can_request = true;
                $user->can_invite = false;
                $user->can_download = true;
                $user->save();
            }

            // PowerUser >= 500GiB and account 1.5 month old
            if ($user->uploaded >= $byteUnits->bytesFromUnit('500GiB') &&
                $user->ratio >= config('other.ratio') &&
                $user->created_at < $current->copy()->subDays(45)->toDateTimeString() &&
                $user->group_id != UserGroups::POWERUSER->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = UserGroups::POWERUSER->value;
                $user->save();
            }

            // SuperUser >= 2TiB and account 3 month old
            if ($user->uploaded >= $byteUnits->bytesFromUnit('2TiB') &&
                $user->ratio >= config('other.ratio') &&
                $user->created_at < $current->copy()->subDays(90)->toDateTimeString() &&
                $user->group_id != UserGroups::SUPERUSER->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = UserGroups::SUPERUSER->value;
                $user->save();
            }

            // ExtremeUser >= 5TiB and account 5 month old
            if ($user->uploaded >= $byteUnits->bytesFromUnit('5TiB') &&
                $user->ratio >= config('other.ratio') &&
                $user->created_at < $current->copy()->subDays(150)->toDateTimeString() &&
                $user->group_id != UserGroups::EXTREMEUSER->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = UserGroups::EXTREMEUSER->value;
                $user->save();
            }

            // InsaneUser >= 10TiB and account 8 month old
            if ($user->uploaded >= $byteUnits->bytesFromUnit('10TiB') &&
                $user->ratio >= config('other.ratio') &&
                $user->created_at < $current->copy()->subDays(240)->toDateTimeString() &&
                $user->group_id != UserGroups::INSANEUSER->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = UserGroups::INSANEUSER->value;
                $user->save();
            }

            // Seeder Seedsize >= 20TiB and account 12 month old and seedtime average 30 days or better
            if ($user->seedingTorrents()->sum('size') >= $byteUnits->bytesFromUnit('20TiB') &&
                $user->ratio >= config('other.ratio') &&
                round($user->history()->sum('seedtime') / max(1, $hiscount)) > 2_592_000 &&
                $user->created_at < $current->copy()->subDays(365)->toDateTimeString() &&
                $user->group_id != UserGroups::SEEDER->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = UserGroups::SEEDER->value;
                $user->save();
            }

            // Archivist Seedsize >= 50TiB and account 545 days and seedtime average 60 days or better
            if ($user->seedingTorrents()->sum('size') >= $byteUnits->bytesFromUnit('50TiB') &&
                $user->ratio >= config('other.ratio') &&
                round($user->history()->sum('seedtime') / max(1, $hiscount)) > 2_592_000 * 2 &&
                $user->created_at < $current->copy()->subDays(545)->toDateTimeString() &&
                $user->group_id != UserGroups::ARCHIVIST->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = UserGroups::ARCHIVIST->value;
                $user->save();
            }

            // Veteran >= 100TiB and account 2 year old
            if ($user->uploaded >= $byteUnits->bytesFromUnit('100TiB') &&
                $user->ratio >= config('other.ratio') &&
                $user->created_at < $current->copy()->subDays(730)->toDateTimeString() &&
                $user->group_id != UserGroups::VETERAN->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = UserGroups::VETERAN->value;
                $user->save();
            }

            // 检查是否应该升级到KEEPER
            $blurayTorrentsSize = Peer::query()
                ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
                ->where('peers.user_id', '=', $user->id)
                ->where('peers.seeder', '=', 1)
                ->where('peers.active', '=', 1)
                ->whereIn('torrents.type_id', [1, 2])
                ->sum('torrents.size');

            $internalTorrentsSize = Peer::query()
                ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
                ->where('peers.user_id', '=', $user->id)
                ->where('peers.seeder', '=', 1)
                ->where('peers.active', '=', 1)
                ->where('torrents.internal', '=', 1)
                ->sum('torrents.size');

            $TotalTorrentsSize = Peer::query()
                ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
                ->where('peers.user_id', '=', $user->id)
                ->where('peers.seeder', '=', 1)
                ->where('peers.active', '=', 1)
                ->sum('torrents.size');


            // 将字节转换为TB
            $blurayTorrentsSizeTB = $blurayTorrentsSize / (1024 * 1024 * 1024 * 1024);
            $internalTorrentsSizeTB = $internalTorrentsSize / (1024 * 1024 * 1024 * 1024);
            $totalTorrentsSizeTB = $TotalTorrentsSize / (1024 * 1024 * 1024 * 1024);


            // 升级到KEEPER的条件
            if (($blurayTorrentsSizeTB >= 15 || $internalTorrentsSizeTB >= 10 || $totalTorrentsSizeTB >= 20 ) &&
                $user->group_id != UserGroups::KEEPER->value) {
                $user->group_id = UserGroups::KEEPER->value;
                $user->save();
            }

            // 如果是KEEPER但不再满足条件，则根据其他规则自动降级
            if ($user->group_id == UserGroups::KEEPER->value &&
                $blurayTorrentsSizeTB < 15 && $internalTorrentsSizeTB < 10 && $totalTorrentsSizeTB < 20) {

                // 检查是否满足Veteran等级的条件
                if ($user->uploaded >= $byteUnits->bytesFromUnit('100TiB') &&
                    $user->ratio >= config('other.ratio') &&
                    $user->created_at < $current->copy()->subDays(730)->toDateTimeString()) {
                    $user->group_id = UserGroups::VETERAN->value;
                }
                // 检查是否满足InsaneUser等级的条件
                elseif ($user->uploaded >= $byteUnits->bytesFromUnit('10TiB') &&
                    $user->ratio >= config('other.ratio') &&
                    $user->created_at < $current->copy()->subDays(240)->toDateTimeString()) {
                    $user->group_id = UserGroups::INSANEUSER->value;
                }
                // 检查是否满足ExtremeUser等级的条件
                elseif ($user->uploaded >= $byteUnits->bytesFromUnit('5TiB') &&
                    $user->ratio >= config('other.ratio') &&
                    $user->created_at < $current->copy()->subDays(150)->toDateTimeString()) {
                    $user->group_id = UserGroups::EXTREMEUSER->value;
                }
                // 检查是否满足SuperUser等级的条件
                elseif ($user->uploaded >= $byteUnits->bytesFromUnit('2TiB') &&
                    $user->ratio >= config('other.ratio') &&
                    $user->created_at < $current->copy()->subDays(90)->toDateTimeString()) {
                    $user->group_id = UserGroups::SUPERUSER->value;
                }
                // 检查是否满足PowerUser等级的条件
                elseif ($user->uploaded >= $byteUnits->bytesFromUnit('500GiB') &&
                    $user->ratio >= config('other.ratio') &&
                    $user->created_at < $current->copy()->subDays(45)->toDateTimeString()) {
                    $user->group_id = UserGroups::POWERUSER->value;
                }
                // 默认降级到User等级
                else {
                    $user->group_id = UserGroups::USER->value;
                }

                $user->save();
            }

            if ($user->wasChanged()) {
                cache()->forget('user:'.$user->passkey);

                Unit3dAnnounce::addUser($user);
            }
        }

        $this->comment('Automated User Group Command Complete');
    }
}
