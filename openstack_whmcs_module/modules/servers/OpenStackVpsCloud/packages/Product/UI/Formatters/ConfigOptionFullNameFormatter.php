<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Formatters;

use ModulesGarden\OpenStackVpsCloud\Components\Container\Container;
use ModulesGarden\OpenStackVpsCloud\Components\Span\Span;
use ModulesGarden\OpenStackVpsCloud\Components\Text\Text;
use ModulesGarden\OpenStackVpsCloud\Components\Text\TextBold;

class ConfigOptionFullNameFormatter
{
    protected const CONFIG_OPTION_NAMES_SEPARATOR = "|";

    public static function buildFullNameContainer(string $optionFullName): Container
    {
        $elements = explode(self::CONFIG_OPTION_NAMES_SEPARATOR, $optionFullName);
        $configOptionSysName = $elements[0];
        $configOptionFriendlyName= $elements[1];

        $span = new Span();

        $span->addElement((new TextBold())->setText($configOptionSysName));

        if (!empty($configOptionFriendlyName))
        {
            $span->addElement((new Text())->setText(self::CONFIG_OPTION_NAMES_SEPARATOR . $elements[1]));
        }

        return $span;
    }
}