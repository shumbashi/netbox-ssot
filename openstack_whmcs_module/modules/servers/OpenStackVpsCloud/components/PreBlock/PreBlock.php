<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\PreBlock;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\AjaxTrait;

/**
 * Class PreBlock
 */
class PreBlock extends AbstractComponent
{
    use AjaxTrait;

    public const COMPONENT = 'PreBlock';

    /**
     * @param string $content
     * @return PreBlock
     */
    public function setContent($content)
    {
        $this->setSlot('content', $content);

        return $this;
    }
}
