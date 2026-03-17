<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\CommandLine\Models;

use ModulesGarden\OpenStackVpsCloud\Core\Models\ExtendedEloquentModel;

/**
 * Class Commands
 * @property $uuid
 * @property $name
 * @property $parent_uuid
 * @property $status
 * @property $action
 */
class Commands extends ExtendedEloquentModel
{
    public const ACTION_NONE     = 'none';
    public const ACTION_REBOOT   = 'reboot';
    public const ACTION_START    = 'start';
    public const ACTION_STOP     = 'stop';
    public const STATUS_ERROR    = 'error';
    public const STATUS_RUNNING  = 'running';
    public const STATUS_PENDING  = 'pending';
    public const STATUS_SLEEPING = 'sleeping';
    public const STATUS_STOPPED  = 'stopped';

    public $incrementing = false;

    /**
     * @var string
     */
    protected $primaryKey = 'name';

    /**
     * Eloquent fillable parameters
     * @var array
     */
    protected $fillable = ['name', 'uuid', 'parent_uuid', 'status', 'action', 'params', 'intervalType', 'interval'];

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'Commands';

    /**
     * @return $this
     */
    public function clearAction()
    {
        $this->setAction(self::ACTION_NONE);

        return $this;
    }

    /**
     * @param $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;
        $this->save();

        return $this;
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        return $this->status === self::STATUS_RUNNING;
    }

    public function isSleeping()
    {
        return $this->status === self::STATUS_SLEEPING;
    }

    /**
     * @return bool
     */
    public function isStopped()
    {
        return $this->status === self::STATUS_STOPPED;
    }

    public function ping()
    {
        $this->save();
    }

    /**
     * @return $this
     */
    public function reboot()
    {
        $this->setAction(self::ACTION_REBOOT);

        return $this;
    }

    /**
     * @return $this
     */
    public function setRunning()
    {
        $this->setStatus(self::STATUS_RUNNING);

        return $this;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        $this->save();

        return $this;
    }

    /**
     * @return $this
     */
    public function setSleeping()
    {
        $this->setStatus(self::STATUS_SLEEPING);

        return $this;
    }

    /**
     * @return $this
     */
    public function setStopped()
    {
        $this->setStatus(self::STATUS_STOPPED);

        return $this;
    }

    /**
     * @return $this
     */
    public function setErrored()
    {
        $this->setStatus(self::STATUS_ERROR);

        return $this;
    }

    public function shouldBeRestared()
    {
        return $this->action === self::ACTION_REBOOT;
    }

    /**
     * @return bool
     */
    public function shouldBeStopped()
    {
        return $this->action === self::ACTION_STOP;
    }

    /**
     * @return $this
     */
    public function start()
    {
        $this->setAction(self::ACTION_START);

        return $this;
    }

    /**
     * @return $this
     */
    public function stop()
    {
        $this->setAction(self::ACTION_STOP);

        return $this;
    }

    /**
     * @return $this
     */
    public function setStartTime()
    {
        $this->started_at = (new \DateTime("NOW"))->format('Y-m-d H:i:s');
        $this->save();

        return $this;
    }

    /**
     * @return $this
     */
    public function setEndTime()
    {
        $this->ended_at = (new \DateTime("NOW"))->format('Y-m-d H:i:s');
        $this->save();

        return $this;
    }

    public function getParamsAttribute()
    {
        if (isset($this->attributes["params"]))
        {
            $decoded = json_decode($this->attributes["data"], true);

            if ($decoded)
            {
                return $decoded;
            }
        }
        return [];
    }

    public function setParamsAttribute(array $value)
    {
        $this->attributes["params"] = json_encode($value);

        return $this;
    }
}
