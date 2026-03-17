<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\TextParagraph;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TextTrait;

class TextParagraph extends AbstractComponent
{
    use TextTrait;

    public const COMPONENT = 'TextParagraph';
}
