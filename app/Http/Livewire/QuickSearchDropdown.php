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

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $search = '%'.str_replace(' ', '%', $this->quicksearchText).'%';

        return view('livewire.quick-search-dropdown', [
            'search_results' => $this->quicksearchText === '' ? [] : match ($this->quicksearchRadio) {
                'albums' => Torrent::query()
                ->where('category_id', '=', 3)
                ->where('name', 'LIKE', $search)
                    ->take(10)
                    ->get(),
                'songs' => Music::query() // 假设您的歌曲信息存储在 Music 表中
                ->select(['id', 'name', 'torrent_id'])
                    ->where('name', 'LIKE', $search)
                    ->take(10)
                    ->get(),
                'artists' => Artist::query()
                    ->select(['id', 'name'])
                    ->where('name', 'LIKE', $search)
                    ->take(10)
                    ->get(),
                default => [],
            },
        ]);
    }
}
