<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Video;
use Illuminate\Pagination\LengthAwarePaginator;

class VideoSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'release_date'; // 默认排序字段
    public $sortDirection = 'desc'; // 默认排序方向

    protected $updatesQueryString = [
        'search' => ['except' => ''],
        'sortField',
        'sortDirection',
        'page' => ['except' => 1],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $videos = collect(); // 默认不加载任何视频数据

        if (!empty($this->search)) {
            // 从 Redis 获取数据
            $videos = collect(Video::getFromRedis());

            $searchTerms = $this->prepareSearchTerms($this->search);

            $videos = $videos->filter(function ($video) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    if (strpos($video->item_number, $term) === false) {
                        return false;
                    }
                }
                return true;
            });

            $videos = $videos->sortBy($this->sortField, SORT_REGULAR, $this->sortDirection === 'desc');

            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 50;
            $currentItems = $videos->slice(($currentPage - 1) * $perPage, $perPage)->all();

            $paginatedItems = new LengthAwarePaginator($currentItems, $videos->count(), $perPage, $currentPage, [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]);
        } else {
            // 没有搜索词时不进行分页
            $paginatedItems = collect();
        }

        return view('livewire.video-search', [
            'videos' => $paginatedItems,
        ]);
    }

    protected function prepareSearchTerms($term)
    {
        if (empty($term)) {
            return [];
        }

        // 将输入的字符串分割为单个字符
        return str_split($term);
    }
}
