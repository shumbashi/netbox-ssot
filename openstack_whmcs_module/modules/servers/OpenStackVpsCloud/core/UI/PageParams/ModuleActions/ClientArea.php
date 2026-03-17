<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\UI\PageParams\ModuleActions;

use ModulesGarden\OpenStackVpsCloud\Core\UI\PageParams\Source\ModuleActionInterface;

class ClientArea implements ModuleActionInterface
{

    public function selectAppropriateParameters(array $params): array
    {
        if (!empty($params['serviceid']))
        {
            return ['id' => $params['serviceid']];
        }

        return [];
    }
}