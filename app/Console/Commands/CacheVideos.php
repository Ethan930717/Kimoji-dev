<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;

class CacheVideos extends Command
{
    protected $signature = 'cache:videos';
    protected $description = 'Cache the videos table data into Redis';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Video::cacheToRedis();
        $this->info('Videos table data has been cached to Redis.');
    }
}
