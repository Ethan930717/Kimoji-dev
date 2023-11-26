<?php

namespace App\Telegram\Commands;

use Telegram\Bot\Commands\Command;

/**
 * Class StartCommand
 */
class StartCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "启动机器人命令";

    /**
     * @inheritdoc
     */
    public function handle()
    {
        // 这里编写处理命令的逻辑
        $response = $this->replyWithMessage(['text' => '欢迎使用我们的Telegram机器人!']);
    }
}
