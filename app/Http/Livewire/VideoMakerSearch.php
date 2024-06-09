<?php

namespace App\Http\Livewire;

use App\Models\VideoMaker;
use Livewire\Component;
use Livewire\WithPagination;

class VideoMakerSearch extends Component
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
        $makers = VideoMaker::when($this->search, function ($query) {
            return $query->where('name', 'like', "%{$this->search}%");
        })
            ->withCount('videos')
            ->paginate(50);

        return view('livewire.video-maker-search', ['makers' => $makers]);
    }
}
