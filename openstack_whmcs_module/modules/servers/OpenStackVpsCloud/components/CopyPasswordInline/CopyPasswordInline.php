<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\CopyPasswordInline;

use ModulesGarden\OpenStackVpsCloud\Components\CopyTextInline\CopyTextInline;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ActionOnClickTrait;

class CopyPasswordInline extends CopyTextInline
{
    use ActionOnClickTrait;

    public const COMPONENT = 'CopyPasswordInline';

    public function setVisible()
    {
        $this->setSlot('is_visible', true);

        return $this;
    }
}
