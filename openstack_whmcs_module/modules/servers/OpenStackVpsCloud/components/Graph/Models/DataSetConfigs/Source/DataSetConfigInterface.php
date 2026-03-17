<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Models\DataSetConfigs\Source;

interface DataSetConfigInterface
{
    public function getName(): string;
    public function getValue();
}