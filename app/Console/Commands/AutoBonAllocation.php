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

use App\Helpers\ByteUnits;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * @see \Tests\Unit\Console\Commands\AutoBonAllocationTest
 */
class AutoBonAllocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:bon_allocation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Allocates Bonus Points To Users Based On Peer Activity.';

    /**
     * Execute the console command.
     */
    /**
     * 根据保种的总体积（GB）计算每小时魔力的增加量。
     *
     * @param  int   $bytes
     * @return float
     */
    private function calculateBonusCoefficient($bytes)
    {
        $gb = $bytes / (1000 * 1000 * 1000); // 将字节转换为 GB
        $bonusPerGb = 0.02; // 每 GB 的魔力增加量

        return $gb * $bonusPerGb; // 总保种 GB 数乘以每 GB 的魔力增加量
    }

    public function handle(ByteUnits $byteUnits): void
    {
        $dyingTorrent = DB::table('peers')
            ->select(DB::raw('count(DISTINCT(peers.torrent_id)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('torrents.seeders', 1)
            ->where('torrents.times_completed', '>', 2)
            ->where('peers.seeder', 1)
            ->where('peers.active', 1)
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $legendaryTorrent = DB::table('peers')
            ->select(DB::raw('count(DISTINCT(peers.torrent_id)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('peers.active', 1)
            ->whereRaw('torrents.created_at < date_sub(now(), interval 12 month)')
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $oldTorrent = DB::table('peers')
            ->select(DB::raw('count(DISTINCT(peers.torrent_id)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('peers.active', 1)
            ->whereRaw('torrents.created_at < date_sub(now(), Interval 6 month)')
            ->whereRaw('torrents.created_at > date_sub(now(), interval 12 month)')
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $hugeTorrent = DB::table('peers')
            ->select(DB::raw('count(DISTINCT(peers.torrent_id)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('peers.active', 1)
            ->where('torrents.size', '>=', $byteUnits->bytesFromUnit('100GiB'))
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $largeTorrent = DB::table('peers')
            ->select(DB::raw('count(DISTINCT(peers.torrent_id)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('peers.active', 1)
            ->where('torrents.size', '>=', $byteUnits->bytesFromUnit('25GiB'))
            ->where('torrents.size', '<', $byteUnits->bytesFromUnit('100GiB'))
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $regularTorrent = DB::table('peers')
            ->select(DB::raw('count(DISTINCT(peers.torrent_id)) as value'), 'peers.user_id')
            ->join('torrents', 'torrents.id', 'peers.torrent_id')
            ->where('peers.seeder', 1)
            ->where('peers.active', 1)
            ->where('torrents.size', '>=', $byteUnits->bytesFromUnit('1GiB'))
            ->where('torrents.size', '<', $byteUnits->bytesFromUnit('25GiB'))
            ->whereRaw('date_sub(peers.created_at,interval 30 minute) < now()')
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $participaintSeeder = DB::table('history')
            ->select(DB::raw('count(*) as value'), 'history.user_id')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2_592_000)
            ->where('history.seedtime', '<', 2_592_000 * 2)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        $teamplayerSeeder = DB::table('history')
            ->select(DB::raw('count(*) as value'), 'history.user_id')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2_592_000 * 2)
            ->where('history.seedtime', '<', 2_592_000 * 3)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        $commitedSeeder = DB::table('history')
            ->select(DB::raw('count(*) as value'), 'history.user_id')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2_592_000 * 3)
            ->where('history.seedtime', '<', 2_592_000 * 6)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        $mvpSeeder = DB::table('history')
            ->select(DB::raw('count(*) as value'), 'history.user_id')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2_592_000 * 6)
            ->where('history.seedtime', '<', 2_592_000 * 12)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        $legendarySeeder = DB::table('history')
            ->select(DB::raw('count(*) as value'), 'history.user_id')
            ->where('history.active', 1)
            ->where('history.seedtime', '>=', 2_592_000 * 12)
            ->groupBy('history.user_id')
            ->get()
            ->toArray();

        $blurayTorrentSizeSum = DB::table('peers')
            ->select(DB::raw('SUM(torrents.size) as total_size'), 'peers.user_id')
            ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
            ->whereIn('torrents.type_id', [1, 2]) // 判断是否为 Blu-ray 种子
            ->where('peers.seeder', 1)
            ->where('peers.active', 1)
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        $internalTorrentSizeSum = DB::table('peers')
            ->select(DB::raw('SUM(torrents.size) as total_size'), 'peers.user_id')
            ->join('torrents', 'torrents.id', '=', 'peers.torrent_id')
            ->where('torrents.internal', 1) // 确保 torrents 是 internal 的
            ->where('peers.seeder', 1)
            ->where('peers.active', 1)
            ->groupBy('peers.user_id')
            ->get()
            ->toArray();

        //Move data from SQL to array

        $array = [];

        foreach ($dyingTorrent as $value) {
            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 2;
            } else {
                $array[$value->user_id] = $value->value * 2;
            }
        }

        foreach ($legendaryTorrent as $value) {
            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 1.5;
            } else {
                $array[$value->user_id] = $value->value * 1.5;
            }
        }

        foreach ($oldTorrent as $value) {
            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 1;
            } else {
                $array[$value->user_id] = $value->value * 1;
            }
        }

        foreach ($hugeTorrent as $value) {
            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.75;
            } else {
                $array[$value->user_id] = $value->value * 0.75;
            }
        }

        foreach ($largeTorrent as $value) {
            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.50;
            } else {
                $array[$value->user_id] = $value->value * 0.50;
            }
        }

        foreach ($regularTorrent as $value) {
            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.25;
            } else {
                $array[$value->user_id] = $value->value * 0.25;
            }
        }

        foreach ($participaintSeeder as $value) {
            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.25;
            } else {
                $array[$value->user_id] = $value->value * 0.25;
            }
        }

        foreach ($teamplayerSeeder as $value) {
            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.50;
            } else {
                $array[$value->user_id] = $value->value * 0.50;
            }
        }

        foreach ($commitedSeeder as $value) {
            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 0.75;
            } else {
                $array[$value->user_id] = $value->value * 0.75;
            }
        }

        foreach ($mvpSeeder as $value) {
            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 1;
            } else {
                $array[$value->user_id] = $value->value * 1;
            }
        }

        foreach ($legendarySeeder as $value) {
            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $value->value * 2;
            } else {
                $array[$value->user_id] = $value->value * 2;
            }
        }

        foreach ($blurayTorrentSizeSum as $value) {
            $bonusCoefficient = $this->calculateBonusCoefficient($value->total_size, 0.015); // 使用 0.015 作为系数

            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $bonusCoefficient;
            } else {
                $array[$value->user_id] = $bonusCoefficient;
            }
        }

        foreach ($internalTorrentSizeSum as $value) {
            // 计算每小时魔力的增加系数
            $bonusCoefficient = $this->calculateBonusCoefficient($value->total_size);

            // 更新 $array
            if (\array_key_exists($value->user_id, $array)) {
                $array[$value->user_id] += $bonusCoefficient;
            } else {
                $array[$value->user_id] = $bonusCoefficient;
            }
        }

        //Move data from array to BonTransactions table
        /*foreach ($array as $key => $value) {
            $log = new BonTransactions();
            $log->bon_exchange_id = 0;
            $log->name = "Seeding Award";
            $log->cost = $value;
            $log->receiver_id = $key;
            $log->comment = "Seeding Award";
            $log->save();
        }*/

        //Move data from array to Users table
        foreach ($array as $key => $value) {
            User::whereKey($key)->update([
                'seedbonus' => DB::raw('seedbonus + '.$value),
            ]);
        }

        $this->comment('Automated BON Allocation Command Complete');
    }
}
