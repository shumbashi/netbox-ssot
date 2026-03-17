<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Badge;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Enums\Color;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\AjaxTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\OutlineTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TextTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TitleTrait;

/**
 * Class Form
 */
class Badge extends AbstractComponent
{
    use AjaxTrait;
    use TextTrait;
    use TitleTrait;
    use OutlineTrait;

    public const COMPONENT = 'Badge';

    public function __construct()
    {
        parent::__construct();

        $this->setType(Color::DEFAULT);
    }

    public function setType(string $type)
    {
        $this->setSlot('type', $type);

        return $this;
    }
}
