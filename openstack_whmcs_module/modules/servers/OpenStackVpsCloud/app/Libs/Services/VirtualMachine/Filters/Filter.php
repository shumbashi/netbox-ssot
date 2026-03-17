<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters;
class Filter
{
    protected ?array $data = [];

    public function __construct(?array $data = [])
    {
        $this->data = $data;
    }

    public function get(): array
    {
        return $this->data;
    }
}
