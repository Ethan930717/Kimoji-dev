<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Torrent;

class PostponeTorrent extends Component
{
    public $torrent;
    public $message;
    public $showModal = false;

    public function mount(Torrent $torrent): void
    {
        $this->torrent = $torrent;
    }

    public function postpone(): void
    {
        // 更新种子状态
        $this->torrent->status = Torrent::POSTPONED;
        $this->torrent->save();

        // 重置状态并关闭模态框
        $this->reset('message');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.postpone-torrent');
    }
}
