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
        return Artist::select(['id', 'image_url', 'name']) // 更新为 image_url
        ->when($this->search !== '', function ($query) {
            return $query->where('name', 'LIKE', '%'.$this->search.'%');
        })
            ->orderBy('name', 'asc') // 根据需要调整排序
            ->paginate(36); // 根据需要调整分页大小
    }

    // 渲染组件视图
    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        return view('livewire.artist-search', [
            'artists' => $this->artists,
        ]);
    }
}
