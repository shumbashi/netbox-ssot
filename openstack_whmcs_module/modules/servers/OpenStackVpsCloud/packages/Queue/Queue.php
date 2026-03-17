<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue;

use Exception;
use ModulesGarden\OpenStackVpsCloud\Core\DependencyInjection;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Exceptions\RunTaskException;

class Queue
{
    /**
     * @var null
     */
    protected $callAfter = null;

    /**
     * @var null
     */
    protected $callBefore = null;

    public function process()
    {
        $queue = DependencyInjection::get(DatabaseQueue::class);

        while ($model = $queue->pop())
        {
            if ($this->callBefore)
            {
                $callback = $this->callBefore;
                $callback($model);
            }

            $job = new Manager($model);

            try {
                $job->fire();
            } catch (RunTaskException $ex) {
                // Do nothing
                // Do not interrupt the execution of the queue
                // Errors logged in fire()
            }

            if ($this->callAfter)
            {
                $callback = $this->callAfter;
                $callback($model);
            }
        }
    }

    /**
     * @param $callable
     * @throws Exception
     */
    public function setCallAfter($callable)
    {
        if (!is_callable($callable))
        {
            throw new Exception('Argument is not callable');
        }

        $this->callAfter = $callable;
    }

    /**
     * @param $callable
     * @throws Exception
     */
    public function setCallBefore($callable)
    {
        if (!is_callable($callable))
        {
            throw new Exception('Argument is not callable');
        }

        $this->callBefore = $callable;
    }
}
