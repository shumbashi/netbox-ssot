<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\Actions;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractActionInterface;

class RedirectToPreviousPage extends AbstractActionInterface
{
    public function toArray(): array
    {
        return [
            'action' => 'redirectToPreviousPage',
        ];
    }
}
