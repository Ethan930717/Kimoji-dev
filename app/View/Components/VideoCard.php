<?php

namespace App\View\Components;

use Illuminate\View\Component;

class VideoCard extends Component
{
    public $video;

    public function __construct($video)
    {
        $this->video = $video;
    }

    public function render()
    {
        return view('components.video-card');
    }
}
