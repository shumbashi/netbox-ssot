<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Container;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ComponentContainerInterface;

/**
 * Class Form
 */
class ContainerNoWrap extends Container implements ComponentContainerInterface
{
    protected $css = 'lu-text-nowrap';
}
