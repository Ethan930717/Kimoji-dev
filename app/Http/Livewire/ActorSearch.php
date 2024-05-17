<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Actor;

class ActorSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name'; // 默认排序字段
    public $sortDirection = 'asc'; // 默认排序方向

    protected $updatesQueryString = [
        'search' => ['except' => ''],
        'sortField',
        'sortDirection',
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }


    public function render()
    {
        $actors = Actor::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('english_name', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(50);

        return view('livewire.actor-search', [
            'actors' => $actors,
        ]);
    }
}
