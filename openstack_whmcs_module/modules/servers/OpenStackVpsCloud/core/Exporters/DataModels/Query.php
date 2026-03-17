<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Exporters\DataModels;

use ModulesGarden\OpenStackVpsCloud\Core\Exporters\Source\DataModelInterface;

class Query extends Collection implements DataModelInterface
{
    public function __construct($query)
    {
        parent::__construct($query->get());
    }
}