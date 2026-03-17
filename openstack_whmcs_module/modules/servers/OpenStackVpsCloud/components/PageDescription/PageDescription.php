<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\PageDescription;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ImageTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TitleTrait;

/**
 * Class Form
 */
class PageDescription extends AbstractComponent
{
    use ImageTrait;
    use TitleTrait;

    public const COMPONENT = 'PageDescription';

    public function setContent(string $content): self
    {
        $this->setSlot('content', $content);

        return $this;
    }
}
