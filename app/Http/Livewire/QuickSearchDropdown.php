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
                'songs' => Music::query()
                    ->select(['id', 'artist_name', 'song_name', 'duration', 'torrent_id']) // 修改了选择的列名以匹配您的表结构
                    ->where('song_name', 'LIKE', $search) // 修改搜索列为 song_name
                    ->take(10)
                    ->get(),
                'artists' => Artist::query()
                    ->select(['id', 'name', 'image_url'])
                    ->where('name', 'LIKE', $search)
                    ->take(10)
                    ->get(),
                default => [],
            },
        ]);
    }
}
