<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\GroupBuilders;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\GroupBuilders\DefaultBuilder;

class ConfigOptionGroupBuilder extends DefaultBuilder
{
    protected function findBuilder($name): string
    {
        $pieces    = array_filter(preg_split('/(?=[A-Z])/', $name));
        $namespace = '\\' . implode('\\', array_slice(explode('\\', __NAMESPACE__), 0, -1));

        while ($pieces)
        {
            $fieldFactoryName = $namespace . '\\FieldFactories\\' . implode($pieces) . 'Factory';

            if (class_exists($fieldFactoryName))
            {
                return $fieldFactoryName;
            }

            array_pop($pieces);
        }

        return parent::findBuilder($name);
    }
}