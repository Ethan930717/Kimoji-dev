<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Artist;
use DB;


class CountrySearch extends Component
{
    public $search = '';

    public function render()
    {
        // 获取国家列表及每个国家的歌手总数
        $countries = Artist::select('country', DB::raw('count(*) as total_artists'))
            ->when($this->search, function ($query) {
                $query->where('country', 'like', '%' . $this->search . '%');
            })
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderBy('country', 'asc')
            ->get();

        return view('livewire.country-search', [
            'countries' => $countries
        ]);
    }
}
