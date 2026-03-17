<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Link;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\UrlTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ComponentInterface;

class Link extends AbstractComponent
{
    use UrlTrait;

    public const COMPONENT = 'Link';

    public function setTitle(string|ComponentInterface $title): self
    {
        $this->setSlot('title', $title);

        return $this;
    }
}