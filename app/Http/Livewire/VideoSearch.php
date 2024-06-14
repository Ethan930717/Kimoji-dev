<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Video;

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
        $currentPage = $this->page;
        $perPage = 50;

        $videos = Video::getFromRedis($currentPage, $perPage, [
            'item_number' => $this->search,
        ], $this->sortField, $this->sortDirection);

        return view('livewire.video-search', [
            'videos' => $videos,
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


