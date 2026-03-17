<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components;

interface ComponentContainerInterface
{
    public function addElement($element): self;
}
