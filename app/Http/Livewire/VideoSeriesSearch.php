<?php

namespace App\Http\Livewire;

use App\Models\VideoSeries;
use Livewire\Component;
use Livewire\WithPagination;

class VideoSeriesSearch extends Component
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
        $series = VideoSeries::when($this->search, function ($query) {
            return $query->where('name', 'like', "%{$this->search}%");
        })
            ->withCount('videos')
            ->paginate(50);

        return view('livewire.video-series-search', ['series' => $series]);
    }
}
