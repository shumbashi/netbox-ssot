<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Graph\Models\DataSetConfigs;

use ModulesGarden\OpenStackVpsCloud\Components\Graph\Models\DataSetConfigs\Source\StringDataSetConfig;

class CubicInterpolationMode extends StringDataSetConfig
{
    public const DEFAULT = "default";
    public const MONOTONE = "monotone";
}