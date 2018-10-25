<?php
namespace App\Queue;

use Cake\Event\Event;
use Cake\Event\EventManager;
use josegonzalez\Queuesadilla\Job;
use Josegonzalez\CakeQueuesadilla\Queue\Queue;

/**
 * QueueManager
 *
 * Like EventManager, but for Queues. Use QueueManager to queue regular Events
 * into a proper job queue that are fired when the worker runs
 */
class QueueManager
{

    /**
     * Queued events. For testing mostly
     *
     * @var array
     */
    protected static $queued = [];

    /**
     * Sets the queued list
     *
     * @param array $queued
     * @return void
     */
    public static function setQueuedList($queued)
    {
        static::$queued = $queued;
    }

    /**
     * Places an event in the job queue
     *
     * @param Event $event
     * @param array $options
     * @return void
     */
    public static function queue(Event $event, array $options = [])
    {
        static::$queued[] = $event;
        Queue::push('\App\Queue\QueueManager::dispatchEvent', [get_class($event), $event->getName(), $event->getData()], $options);
    }

    /**
     * Gets events that were queued during this request
     *
     * @return array
     */
    public static function getQueuedList()
    {
        return static::$queued;
    }

    /**
     * Constructs and dispatches the event from a job
     *
     * ### Data array
     * - 0: event FQCN
     * - 1: event name
     * - 2: event data array
     *
     * @param Job\Base $job Job
     * @return void
     */
    public static function dispatchEvent($job)
    {
        $eventClass = $job->data(0);
        $eventName = $job->data(1);
        $data = $job->data(2, []);

        $event = new $eventClass($eventName, null, $data);
        EventManager::instance()->dispatch($event);
    }
}
