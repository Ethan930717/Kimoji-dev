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

use App\Enums\Usergroup;
use App\Helpers\ByteUnits;
use App\Models\Group;
use App\Models\History;
use App\Models\User;
use App\Models\Peer;
use App\Services\Unit3dAnnounce;
use App\Notifications\UserGroupChanged;
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
            $oldGroupId = $user->group_id;

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

            // Temp Hard Coding of Group Requirements (Config Files To Come) (Upload in Bytes!) (Seedtime in Seconds!)
            $excludedGroups = [Usergroup::INTERNAL->value, Usergroup::KEEPER->value];

            // Leech ratio dropped below sites minimum
            if ($user->ratio < config('other.ratio') &&
                $user->group_id != Usergroup::LEECH->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = Usergroup::LEECH->value;
                $user->can_request = false;
                $user->can_invite = false;
                $user->save();
            }

            // User >= 0 and ratio above sites minimum
            if ($user->uploaded >= 0 &&
                $user->ratio >= config('other.ratio') &&
                $user->group_id != Usergroup::USER->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = Usergroup::USER->value;
                $user->can_request = true;
                $user->can_invite = false;
                $user->can_download = true;
                $user->save();
            }

            // PowerUser >= 500GB
            if ($user->ratio >= config('other.ratio') &&
                $internalTorrentsSizeTB >= 0.5 &&
                $user->group_id != Usergroup::POWERUSER->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = Usergroup::POWERUSER->value;
                $user->save();
            }

            // SuperUser >= 1200GB
            if ($user->ratio >= config('other.ratio') &&
                $internalTorrentsSizeTB >= 1.2 &&
                $user->group_id != Usergroup::SUPERUSER->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = Usergroup::SUPERUSER->value;
                $user->save();
            }

            // ExtremeUser >= 2000GB
            if ($user->ratio >= config('other.ratio') &&
                $internalTorrentsSizeTB >= 2 &&
                $user->group_id != Usergroup::EXTREMEUSER->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = Usergroup::EXTREMEUSER->value;
                $user->save();
            }

            // InsaneUser >= 3000GB and account 8 month old
            if ($user->ratio >= config('other.ratio') &&
                $internalTorrentsSizeTB >= 3 &&
                $user->group_id != Usergroup::INSANEUSER->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = Usergroup::INSANEUSER->value;
                $user->save();
            }

            // Seeder Seedsize >= 20TiB and account 12 month old and seedtime average 30 days or better
            if ($user->ratio >= config('other.ratio') &&
                $internalTorrentsSizeTB >= 4.2 &&
                $user->group_id != Usergroup::SEEDER->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = Usergroup::SEEDER->value;
                $user->save();
            }

            // Archivist Seedsize >= 50TiB and account 545 days and seedtime average 60 days or better
            if ($user->ratio >= config('other.ratio') &&
                $internalTorrentsSizeTB >= 6 &&
                $user->group_id != Usergroup::ARCHIVIST->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = Usergroup::ARCHIVIST->value;
                $user->save();
            }

            // Veteran >= 100TiB and account 2 year old
            if ($user->ratio >= config('other.ratio') &&
                $internalTorrentsSizeTB >= 8 &&
                $user->group_id != Usergroup::VETERAN->value &&
                !\in_array($user->group_id, $excludedGroups)) {
                $user->group_id = Usergroup::VETERAN->value;
                $user->save();
            }

            // 检查是否应该升级到KEEPER
            // 确保用户当前不是INTERNAL等级
            if ($user->group_id != Usergroup::INTERNAL->value) {
                // 升级到KEEPER的条件
                if (($blurayTorrentsSizeTB >= 15 || $internalTorrentsSizeTB >= 10 || $totalTorrentsSizeTB >= 20) &&
                    $user->group_id != Usergroup::KEEPER->value) {
                    $user->group_id = Usergroup::KEEPER->value;
                    $user->save();
                }
            }

            // 如果是KEEPER但不再满足条件，则根据其他规则自动降级
            if ($user->group_id == Usergroup::KEEPER->value &&
                $blurayTorrentsSizeTB < 15 && $internalTorrentsSizeTB < 10 && $totalTorrentsSizeTB < 20) {
                // 检查是否满足Veteran等级的条件
                if ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 8) {
                    $user->group_id = Usergroup::VETERAN->value;
                }
                // 降级到Archivist的条件
                elseif ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 6) {
                    $user->group_id = Usergroup::ARCHIVIST->value;
                }
                // 降级到Seeder的条件
                elseif ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 4.2) {
                    $user->group_id = Usergroup::SEEDER->value;
                }

                // 检查是否满足InsaneUser等级的条件
                elseif ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 3) {
                    $user->group_id = Usergroup::INSANEUSER->value;
                }
                // 检查是否满足ExtremeUser等级的条件
                elseif ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 2) {
                    $user->group_id = Usergroup::EXTREMEUSER->value;
                }
                // 检查是否满足SuperUser等级的条件
                elseif ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 1.2) {
                    $user->group_id = Usergroup::SUPERUSER->value;
                }
                // 检查是否满足PowerUser等级的条件
                elseif ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 0.5) {
                    $user->group_id = Usergroup::POWERUSER->value;
                }
                // 默认降级到User等级
                else {
                    $user->group_id = Usergroup::USER->value;
                }

                $user->save();
            }

            // 检查是否应该升级到INTERNAL
            // 确保用户当前不是KEEPER等级
            if ($user->group_id != Usergroup::KEEPER->value) {
                $nonInternalTorrentCount = $user->torrents()
                    ->where('internal', 0)
                    ->where('status', 1)
                    ->count();
                $recentNonInternalTorrentCount = $user->torrents()
                    ->where('internal', 0)
                    ->where('status', 1)
                    ->where('created_at', '>=', Carbon::now()->subMonth())
                    ->count();

                if ($user->group_id != Usergroup::INTERNAL->value) {
                    if ($nonInternalTorrentCount >= 200 ||
                        ($user->hasBeenDemotedFromInternal && $recentNonInternalTorrentCount >= 60)) {
                        $user->group_id = Usergroup::INTERNAL->value;
                        $user->save();
                    }
                }
            }

            // 如果是INTERNAL但不再满足条件，则根据其他规则自动降级
            if ($user->group_id == Usergroup::INTERNAL->value &&
                $recentNonInternalTorrentCount < 30) {
                // 检查是否满足Veteran等级的条件
                if ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 8) {
                    $user->group_id = Usergroup::VETERAN->value;
                }
                // 降级到Archivist的条件
                elseif ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 6) {
                    $user->group_id = Usergroup::ARCHIVIST->value;
                }
                // 降级到Seeder的条件
                elseif ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 4.2) {
                    $user->group_id = Usergroup::SEEDER->value;
                }

                // 检查是否满足InsaneUser等级的条件
                elseif ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 3) {
                    $user->group_id = Usergroup::INSANEUSER->value;
                }
                // 检查是否满足ExtremeUser等级的条件
                elseif ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 2) {
                    $user->group_id = Usergroup::EXTREMEUSER->value;
                }
                // 检查是否满足SuperUser等级的条件
                elseif ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 1.2) {
                    $user->group_id = Usergroup::SUPERUSER->value;
                }
                // 检查是否满足PowerUser等级的条件
                elseif ($user->ratio >= config('other.ratio') &&
                    $internalTorrentsSizeTB >= 0.5) {
                    $user->group_id = Usergroup::POWERUSER->value;
                }
                // 默认降级到User等级
                else {
                    $user->group_id = Usergroup::USER->value;
                }
                $user->hasBeenDemotedFromInternal = 1;
                $user->save();
            }

            if ($user->group_id != $oldGroupId) {
                cache()->forget('user:'.$user->passkey);

                $user->notify(new UserGroupChanged($user, $oldGroupId, $user->group_id));
                Unit3dAnnounce::addUser($user);
            }
        }

        $this->comment('Automated User Group Command Complete');
    }
}
