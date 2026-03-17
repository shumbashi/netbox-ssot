<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Text;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\CssContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TextTrait;

class Text extends AbstractComponent
{
    use TextTrait;
    use CssContainerTrait;

    public const COMPONENT = 'Text';
}
