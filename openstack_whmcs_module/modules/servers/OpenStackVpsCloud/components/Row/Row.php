<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Row;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\AjaxTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ComponentsContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\CssContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ComponentContainerInterface;

class Row extends AbstractComponent implements ComponentContainerInterface
{
    use AjaxTrait;
    use ComponentsContainerTrait;
    use CssContainerTrait;

    public const COMPONENT = 'Row';
}
