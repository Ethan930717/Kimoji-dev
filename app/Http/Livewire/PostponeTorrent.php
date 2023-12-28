<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Torrent;

class PostponeTorrent extends Component
{
    public $torrent;
    public $message;
    public $showModal = false;

    public function mount(Torrent $torrent)
    {
        $this->torrent = $torrent;
    }

    public function postpone()
    {
        // 处理延期逻辑
        $this->torrent->status = Torrent::POSTPONED;
        $this->torrent->save();

        // 关闭模态框
        $this->showModal = false;
        $this->reset('message');
    }

    public function render()
    {
        return view('livewire.postpone-torrent');
    }
}