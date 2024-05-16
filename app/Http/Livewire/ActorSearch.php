<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Actor;

class ActorSearch extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => '']
    ];

    public function render()
    {
        $actors = Actor::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('english_name', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.actor-search', [
            'actors' => $actors
        ]);
    }
}
