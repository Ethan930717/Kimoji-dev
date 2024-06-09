<?php

namespace App\Http\Livewire;

use App\Models\Artist;
use Livewire\Component;
use Livewire\WithPagination;


class ArtistSearch extends Component
{
    use WithPagination;

    public $search = '';

    // 当搜索字段更新时重置分页
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    // 提供艺术家数据的属性，根据搜索查询动态加载
    public function getArtistsProperty(): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $page = $this->page; // 获取当前分页页码
        $searchTerm = $this->search; // 获取当前搜索词

        // 缓存键包括搜索词和页码，确保每个搜索词和页码组合的结果被唯一缓存
        $cacheKey = 'artists_search_'. $searchTerm .'_page_'. $page;

        return cache()->remember($cacheKey, 3600, function () use ($searchTerm) {
            return Artist::select(['id', 'image_url', 'name'])
                ->when($searchTerm !== '', fn ($query) => $query->where('name', 'LIKE', '%'.$searchTerm.'%'))
                ->orderBy('name', 'asc')
                ->paginate(50);
        });
    }


    // 渲染组件视图
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.artist-search', [
            'artists' => $this->artists,
        ]);
    }
}
