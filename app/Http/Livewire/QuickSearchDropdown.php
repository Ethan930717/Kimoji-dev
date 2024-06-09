<?php

namespace App\Http\Livewire;

use App\Models\Music;
use App\Models\Artist;
use App\Models\Torrent;
use Livewire\Component;

class QuickSearchDropdown extends Component
{
    public string $quicksearchRadio = 'movies';

    public string $quicksearchText = '';

    public function mount(): void
    {
        $this->quicksearchRadio = 'albums';
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $search = '%'.str_replace(' ', '%', $this->quicksearchText).'%';
        $cacheKey = 'search_'.$this->quicksearchRadio.'_'.md5($this->quicksearchText);

        $search_results = cache()->remember($cacheKey, 3600, function () use ($search) {
            return $this->quicksearchText === '' ? [] : match ($this->quicksearchRadio) {
                'albums' => Torrent::query()
                    ->where('category_id', '=', 3)
                    ->where('name', 'LIKE', $search)
                    ->take(50)
                    ->get(),
                'songs' => Music::query()
                    ->select(['id', 'artist_name', 'song_name', 'duration', 'torrent_id'])
                    ->where('song_name', 'LIKE', $search)
                    ->take(50)
                    ->get(),
                'artists' => Artist::query()
                    ->select(['id', 'name', 'image_url'])
                    ->where('name', 'LIKE', $search)
                    ->take(50)
                    ->get(),
                default => [],
            };
        });

        return view('livewire.quick-search-dropdown', [
            'search_results' => $search_results,
        ]);
    }
}
