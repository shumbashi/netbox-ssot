<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\FormLabel;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ComponentsContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\CssContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TextTrait;

/**
 * Class Form
 */
class FormLabel extends AbstractComponent
{
    use ComponentsContainerTrait;
    use CssContainerTrait;
    use TextTrait;

    public const COMPONENT = 'FormLabel';
}
