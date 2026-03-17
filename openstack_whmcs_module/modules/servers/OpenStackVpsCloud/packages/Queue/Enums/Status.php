<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums;

class Status
{
    public const ERROR      = 'error';
    public const FINISHED   = 'finished';
    public const RUNNING    = 'running';
    public const WAITING    = 'waiting';
    public const PENDING    = 'pending';
    public const CANCELLED  = 'cancelled';
    public const FAILED     = 'failed';
}