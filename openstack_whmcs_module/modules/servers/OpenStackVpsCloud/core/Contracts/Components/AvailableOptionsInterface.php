<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components;

interface AvailableOptionsInterface
{
    public function setOptions(array $options): self;
}
