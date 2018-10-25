<?php
namespace App\Shell;

use Cake\Datasource\ConnectionManager;
use Josegonzalez\CakeQueuesadilla\Shell\QueuesadillaShell;

class WorkerShell extends QueuesadillaShell
{
    /**
     * Retrieves a queue worker
     *
     * Prevents database timeout by disconnecting after jobs
     *
     * @param \josegonzalez\Queuesadilla\Engine\Base $engine engine to run
     * @param \Psr\Log\LoggerInterface $logger logger
     * @return \josegonzalez\Queuesadilla\Worker\Base
     */
    public function getWorker($engine, $logger)
    {
        $worker = parent::getWorker($engine, $logger);
        $worker->attachListener('Worker.job.success', function ($event) {
            ConnectionManager::get('default')->disconnect();
        });
        $worker->attachListener('Worker.job.failure', function ($event) {
            ConnectionManager::get('default')->disconnect();
        });

        return $worker;
    }
}
