<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Formatters;

use ModulesGarden\OpenStackVpsCloud\Components\Text\TextNoWrap as TextNoWrapComponent;

class TextNoWrap
{
    public function __invoke($fieldName, $row, $fieldValue, $raw)
    {
        return empty($fieldValue) ? '-' : (new TextNoWrapComponent())->setText($fieldValue);
    }
}