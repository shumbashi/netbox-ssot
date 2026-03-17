<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\BlockZeroData;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\DescriptionTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TitleTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ComponentContainerInterface;

class BlockZeroData extends Container implements ComponentContainerInterface
{
    use TitleTrait;
    use DescriptionTrait;

    public const COMPONENT = 'BlockZeroData';

    public function setIcon(string $icon): self
    {
        $this->setSlot('icon', $icon);

        return $this;
    }
}
