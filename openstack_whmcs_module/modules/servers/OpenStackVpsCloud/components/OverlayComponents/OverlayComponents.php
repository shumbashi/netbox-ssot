<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\OverlayComponents;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

use ModulesGarden\OpenStackVpsCloud\Core\UI\Interfaces\ClientArea;

class OverlayComponents extends Container implements AdminAreaInterface, ClientAreaInterface
{
    public const COMPONENT = 'OverlayComponents';
}
