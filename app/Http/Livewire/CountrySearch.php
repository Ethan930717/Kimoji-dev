<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Artist;

class CountrySearch extends Component
{
    public $search = '';

    public function render()
    {
        $countries = Artist::select('country')
            ->when($this->search, function ($query) {
                $query->where('country', 'like', '%' . $this->search . '%');
            })
            ->whereNotNull('country')
            ->distinct()
            ->orderBy('country', 'asc')
            ->get();

        return view('livewire.country-search', [
            'countries' => $countries
        ]);
    }
}
