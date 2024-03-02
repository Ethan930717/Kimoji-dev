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

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Torrent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

/**
 * @property \Illuminate\Database\Eloquent\Collection<int, Torrent> $works
 * @property array<string, string>                                  $metaTypes
 */
class Top10 extends Component
{
    public string $metaType = 'music_meta';

    public string $interval = 'day';

    /**
     * @var array<string, mixed>
     */
    protected $queryString = [
        'metaType' => ['except' => 'music_meta'],
        'interval' => ['except' => 'day'],
    ];

    /**
     * @var array<string, string>
     */
    protected $rules = [
        'metaType' => 'in:music_meta',
        'interval' => 'in:day,week,month,year,all',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Torrent>
     */
    final public function getWorksProperty(): Collection
    {
        $this->validate();

        return cache()->remember(
            'top10-'.$this->interval,
            3600,
            fn () => Torrent::query()
                // 假设音乐没有特定的关联模型，所以这里去除了 with() 调用
                ->select([
                    'torrents.id', // 明确指定选择torrents表的id字段
                    DB::raw('MIN(torrents.category_id) as category_id'),
                    DB::raw('COUNT(history.id) as download_count'), // 计算下载次数
                ])
                ->join('history', 'history.torrent_id', '=', 'torrents.id')
                // 这里去除了对 tmdb 字段的条件，因为音乐资源没有 tmdb
                ->when($this->interval === 'day', fn ($query) => $query->whereBetween('history.completed_at', [now()->subDay(), now()]))
                ->when($this->interval === 'week', fn ($query) => $query->whereBetween('history.completed_at', [now()->subWeek(), now()]))
                ->when($this->interval === 'month', fn ($query) => $query->whereBetween('history.completed_at', [now()->subMonth(), now()]))
                ->when($this->interval === 'year', fn ($query) => $query->whereBetween('history.completed_at', [now()->subYear(), now()]))
                ->when($this->interval === 'all', fn ($query) => $query->whereNotNull('history.completed_at'))
                ->whereIn('torrents.category_id', Category::select('id')->where('music_meta', '=', true))
                ->where('torrents.size', '>', 1000 * 1000 * 1000) // 继续保留大小过滤以排除过小的文件
                ->groupBy('torrents.id') // 按照torrents.id分组
                ->orderByRaw('COUNT(history.id) DESC') // 根据下载次数降序排序
                ->limit(10) // 根据需求调整显示的数量
                ->get()
        );
    }

    /**
     * @return array<string, string>
     */
    final public function getMetaTypesProperty(): array
    {
        return [
            __('mediahub.music') => 'music_meta', // 确保`mediahub.music`在你的语言文件中有定义
        ];
    }

    final public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.top10', [
            'user'      => auth()->user(),
            'works'     => $this->works,
            'metaTypes' => $this->metaTypes,
        ]);
    }
}
