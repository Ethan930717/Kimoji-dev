<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Torrent;

class PostponeTorrent extends Component
{
    public $torrentId;
    public $message;
    public $showModal = false;

    public function mount($torrentId)
    {
        $this->torrentId = $torrentId;
    }

    public function postpone()
    {
        $torrent = Torrent::find($this->torrentId);
        if ($torrent) {
            $torrent->status = Torrent::POSTPONED;
            $torrent->save();

            // 重置表单和关闭模态框
            $this->reset('message');
            $this->showModal = false;

            // 可以添加其他处理，如通知用户
        }
    }

    public function render()
    {
        return view('livewire.postpone-torrent');
    }
}

