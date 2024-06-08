<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VideoGenre;

class VideoGenreSearch extends Component
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
        $genres = VideoGenre::when($this->search, function($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
            ->withCount('videos')
            ->paginate(50);

        return view('livewire.video-genre-search', ['genres' => $genres]);
    }
}
