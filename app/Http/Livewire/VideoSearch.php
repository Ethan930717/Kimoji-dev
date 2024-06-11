<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Video;
use Illuminate\Support\Facades\Cache;

class VideoSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'item_number'; // 默认排序字段
    public $sortDirection = 'asc'; // 默认排序方向

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
        $searchTerm = $this->prepareSearchTerm($this->search);
        $cacheKey = $this->generateCacheKey();

        $videos = Cache::remember($cacheKey, 3600, function () use ($searchTerm) {
            if (empty($searchTerm)) {
                return Video::orderBy($this->sortField, $this->sortDirection)->paginate(50);
            } else {
                return Video::where(function($query) use ($searchTerm) {
                    foreach ($searchTerm as $term) {
                        $query->orWhere('item_number', 'LIKE', $term);
                    }
                })
                    ->orWhere('actor_name', 'LIKE', '%' . $this->search . '%')
                    ->orderBy($this->sortField, $this->sortDirection)
                    ->paginate(50);
            }
        });

        return view('livewire.video-search', [
            'videos' => $videos,
        ]);
    }

    protected function prepareSearchTerm($term)
    {
        if (empty($term)) {
            return [];
        }

        $terms = [];
        $chars = str_split($term);

        for ($i = 0; $i < count($chars); $i++) {
            $terms[] = '%' . implode('%', array_slice($chars, $i)) . '%';
        }

        return $terms;
    }

    protected function generateCacheKey()
    {
        return 'videos_search_' . md5($this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->page);
    }
}
