<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\ModulesGardenConnectionButton;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TextTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\UrlTrait;
use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ActionOnClickTrait;

class ModulesGardenConnectionButton extends Container
{
    use ActionOnClickTrait;
    use UrlTrait;
    use TextTrait;

    public const COMPONENT = 'ModulesGardenConnectionButton';
}
