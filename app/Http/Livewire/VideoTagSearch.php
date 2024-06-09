<?php

namespace App\Http\Livewire;

use App\Models\VideoTag;
use Livewire\Component;
use Livewire\WithPagination;

class VideoTagSearch extends Component
{
    use WithPagination;

    public $search = '';

    protected $updatesQueryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $tags = VideoTag::when($this->search, function ($query) {
            return $query->where('name', 'like', "%{$this->search}%");
        })
            ->withCount('videos')
            ->paginate(50);

        return view('livewire.video-tag-search', ['tags' => $tags]);
    }
}
