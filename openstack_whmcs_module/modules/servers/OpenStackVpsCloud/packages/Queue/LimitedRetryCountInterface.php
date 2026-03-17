<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue;

interface LimitedRetryCountInterface
{
    function maxRetryCount():int;
}