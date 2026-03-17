<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Actions;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractActionInterface;

class ReloadParent extends AbstractActionInterface
{
    public function toArray(): array
    {
        return [
            'action' => 'emit',
            'event'  => 'reload-parent',
        ];
    }
}
