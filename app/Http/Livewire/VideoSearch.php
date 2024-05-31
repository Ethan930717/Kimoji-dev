<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Video;
use Illuminate\Support\Facades\Cache;
use Livewire\WithPagination;

class VideoSearch extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $searchTerm = $this->prepareSearchTerm($this->search);

        if (empty($searchTerm)) {
            $videos = Video::paginate(100); // 使用分页，每页显示10个结果
        } else {
            $videos = Cache::remember("videos_search_{$this->search}", 3600, function () use ($searchTerm) {
                return Video::where('item_number', 'REGEXP', $searchTerm)
                    ->orWhere('actor_name', 'like', '%' . $this->search . '%')
                    ->paginate(100); // 使用分页，每页显示10个结果
            });
        }

        return view('livewire.video-search', [
            'videos' => $videos,
        ]);
    }

    protected function prepareSearchTerm($term)
    {
        if (empty($term)) {
            return '';
        }

        // 将输入的字符串分割为单个字符，并用 .* 连接，形成正则表达式
        return implode('.*', str_split($term));
    }
}
