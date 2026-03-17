<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Contracts\Events;

interface ListenerInterface
{
    public function handle($payload = []);
}
