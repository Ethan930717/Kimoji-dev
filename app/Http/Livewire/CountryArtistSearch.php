<?php

namespace App\Http\Livewire;

use Livewire\Component;

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
        $artists = Artist::where('name', 'like', '%' . $this->search . '%')
            ->where('country', '=', $this->countryName)
            ->get();

        return view('livewire.country-artist-search', [
            'artists' => $artists
        ]);
    }
}

