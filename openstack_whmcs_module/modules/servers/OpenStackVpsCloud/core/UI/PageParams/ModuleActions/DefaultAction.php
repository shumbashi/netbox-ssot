<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI\PageParams\ModuleActions;

use ModulesGarden\OpenStackVpsCloud\Core\UI\PageParams\Source\ModuleActionInterface;

class DefaultAction implements ModuleActionInterface
{

    public function selectAppropriateParameters(array $params): array
    {
        return [];
    }
}