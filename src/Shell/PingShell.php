<?php
namespace App\Shell;

use App\Queue\QueueManager;
use Cake\Console\Shell;
use Cake\Event\Event;

class PingShell extends Shell
{
    public function main(...$args)
    {
        $word = empty($this->args) ? 'pong' : implode(' ', $this->args);
        QueueManager::queue(new Event('test', null, ['word' => $word]));
    }
}
