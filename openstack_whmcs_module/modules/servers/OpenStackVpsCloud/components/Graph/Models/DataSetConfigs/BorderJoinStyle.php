<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Models\DataSetConfigs;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Models\DataSetConfigs\Source\StringDataSetConfig;

class BorderJoinStyle extends StringDataSetConfig
{
    public const ROUND = "round";
    public const BEVEL = "bevel";
    public const MITER = "miter";

}