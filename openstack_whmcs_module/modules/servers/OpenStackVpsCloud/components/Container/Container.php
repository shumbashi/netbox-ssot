<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Container;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\AjaxTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\BorderTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ComponentsContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\CssContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ComponentContainerInterface;

/**
 * Class Form
 */
class Container extends AbstractComponent implements ComponentContainerInterface
{
    use AjaxTrait;
    use ComponentsContainerTrait;
    use CssContainerTrait;

    public const COMPONENT = 'Container';

    public function setContent($content)
    {
        $this->setSlot('content', $content);
    }
}
