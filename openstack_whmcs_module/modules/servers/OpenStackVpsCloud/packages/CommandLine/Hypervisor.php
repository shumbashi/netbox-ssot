<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\CommandLine;

use Exception;
use ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Models\Commands;

/**
 * Class Hypervisor
 * @todo - clean up!
 */
class Hypervisor
{
    /**
     * @var int
     */
    protected $counter = 0;

    /**
     * @var Commands
     */
    protected $entity = null;

    /**
     * command name
     * @var null
     */
    protected $name = null;

    /**
     * command params
     * @var null
     */
    protected $params = null;

    /**
     * @var int
     */
    protected $sleepInterval = 5;
    protected $tickTime = 1;

    /**
     * Hypervisor constructor.
     * @param $name
     * @param $params
     */
    public function __construct($name, $params)
    {
        $this->name   = $name;
        $this->params = $params;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function checkIfRunning()
    {
        if (!$this->isRunning())
        {
            throw new Exception('Script is not running');
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isStopped()
    {
        $this->getEntity()
            ->shouldBeStopped();
    }

    public function shouldBeStopped()
    {
        return $this->getEntity()
            ->shouldBeStopped();
    }


    protected function getEntity($force = false)
    {
        if ($this->entity && $force === false)
        {
            return $this->entity;
        }

        $this->entity = Commands::where('name', $this->name)->first();

        if (!$this->entity)
        {
            $this->entity       = new Commands();
            $this->entity->name = $this->name;
            $this->entity->uuid = uniqid();
            $this->entity->save();
        }

        return $this->entity;
    }

    /**
     * Lock script
     * @return $this
     * @throws Exception
     */
    public function lock()
    {
        if ($this->isActive())
        {
            throw new Exception('Cannot create lock. Command already running');
        }

        $this->registerTickHandler();
        $this->registerSignalHandler();

        $this->getEntity()
            ->setRunning()
            ->setStartTime();

        return $this;
    }

    /**
     * Returns true if command is running or sleeping
     * @return bool
     */
    public function isActive()
    {
        $entity = $this->getEntity();
        $diff   = time() - $entity->updated_at->timestamp;

        if ($entity->isSleeping() && $diff <= $this->sleepInterval)
        {
            return true;
        }

        if ($entity->isRunning() && $entity->updated_at->timestamp + $this->tickTime > time())
        {
            return true;
        }

        return false;
    }

    protected function registerTickHandler()
    {
        $tick = new Tick(function() {
            $this->ping();
        }, 10);
        $tick->start();
    }

    /**
     * Update information about running command
     * @return $this
     */
    public function ping()
    {
        $this->getEntity()
            ->ping();

        return $this;
    }

    /**
     * Mark process as stopped and exit. This function will be called when you press ctrl c from console
     */
    protected function registerSignalHandler()
    {
        if (!function_exists('pcntl_signal'))
        {
            return;
        }

        if (function_exists('pcntl_async_signals'))
        {
            pcntl_async_signals(true);
        }

        $exit = function() {
            $this->getEntity()->setStopped();
            exit;
        };

        pcntl_signal(SIGINT, $exit);
        pcntl_signal(SIGHUP, $exit);
        pcntl_signal(SIGUSR1, $exit);
    }

    /**
     * @param $interval
     * @return $this
     */
    public function sleep($interval)
    {
        $this->getEntity()
            ->setSleeping();

        /**
         * We need run tick function during sleep, so we run many sleep function in loop
         */
        $counter = 0;
        while ($counter < $interval)
        {
            if ($interval <= $this->sleepInterval)
            {
                $time = $interval;
            }
            else
            {
                $time = $this->sleepInterval;
            }

            $counter += $time;

            sleep($time);
        }

        $this->getEntity()
            ->setRunning();

        return $this;
    }

    /**
     * Unlock script
     * @return $this
     */
    public function unlock()
    {
        $this->getEntity()
            ->setStopped()
            ->setEndTime()
            ->clearAction();

        return $this;
    }
}
