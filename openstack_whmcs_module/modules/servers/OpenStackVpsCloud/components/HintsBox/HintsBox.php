<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\HintsBox;

use ModulesGarden\OpenStackVpsCloud\Components\Hint\Hint;
use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ComponentsContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\CssContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TitleTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ToolbarTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;

class HintsBox extends AbstractComponent implements AdminAreaInterface
{
    use TitleTrait;
    use ToolbarTrait;
    use ComponentsContainerTrait;
    use CssContainerTrait;

    public const COMPONENT = 'HintsBox';

    public function addHint(Hint $hint): self
    {
        $this->addComponent('hints', $hint);

        return $this;
    }
}