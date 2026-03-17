<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\Creation;

class CreationNetworks extends Serializer
{
    public string $port;

    public function setPort(string $port): self
    {
        $this->port = $port;
        return $this;
    }
}