<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Video;

class VideoSearch extends Component
{
    public $search = '';

    public function render()
    {
        $searchTerm = $this->prepareSearchTerm($this->search);

        $videos = Video::where('item_number', 'REGEXP', $searchTerm)
            ->orWhere('actor_name', 'like', '%' . $this->search . '%')
            ->get();

        return view('livewire.video-search', [
            'videos' => $videos,
        ]);
    }

    protected function prepareSearchTerm($term)
    {
        // 将输入的字符串分割为单个字符，并用 .* 连接，形成正则表达式
        return implode('.*', str_split($term));
    }
}