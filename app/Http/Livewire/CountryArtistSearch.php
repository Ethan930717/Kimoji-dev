<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Artist;

class CountryArtistSearch extends Component
{
    public $search = '';
    public $countryName;

    public function mount($countryName)
    {
        $this->countryName = $countryName;
    }

    public function render()
    {
        $cacheKey = 'artists_in_' . $this->countryName . '_search_' . $this->search;

        $artists = cache()->remember($cacheKey, 60, function () {
            return Artist::where('name', 'like', '%' . $this->search . '%')
                ->where('country', '=', $this->countryName)
                ->get();
        });

        return view('livewire.country-artist-search', [
            'artists' => $artists
        ]);
    }
}
