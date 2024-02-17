<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ResetDailyListenCount extends Command
{
    protected $signature = 'reset:daily-listen-count';
    protected $description = 'Reset daily listen count for all users';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        User::query()->update(['daily_listen_count' => 0]);
        $this->info('Daily listen counts have been reset.');
    }
}
