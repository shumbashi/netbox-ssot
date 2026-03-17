<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Widget;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TitleTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ToolbarTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ComponentContainerInterface;

class Widget extends Container implements ComponentContainerInterface
{
    use TitleTrait;
    use ToolbarTrait;

    public const COMPONENT = 'Widget';
}
