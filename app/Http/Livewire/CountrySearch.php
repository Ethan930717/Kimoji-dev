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
        $searchKey = 'search_' . $this->search;
        $countries = cache()->remember($searchKey, 60, function () {
            return Artist::select('country', DB::raw('count(*) as total_artists'))
                ->when($this->search, function ($query) {
                    $query->where('country', 'like', '%' . $this->search . '%');
                })
                ->whereNotNull('country')
                ->groupBy('country')
                ->orderBy('country', 'asc')
                ->get();
        });

        return view('livewire.country-search', [
            'countries' => $countries
        ]);
    }
}
