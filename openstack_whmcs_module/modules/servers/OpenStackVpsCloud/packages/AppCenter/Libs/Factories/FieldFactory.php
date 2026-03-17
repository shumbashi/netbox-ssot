<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Factories;

use ModulesGarden\OpenStackVpsCloud\Components\TextArea\TextArea;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\Models\AppConfigItem;

class FieldFactory
{
    public static function forItem(AppConfigItem $item, bool $useLoader = true): ?FormFieldInterface
    {
        $fieldClass = $item->getField();

        if ($fieldClass) {
            $field = new $fieldClass;
        } else {
            $field = new TextArea();
        }

        $loadField = $item->getLoader();
        if ($useLoader && $fieldClass && is_callable($loadField)) {
            $loadField($item, $field);
        }

        if ($item->getSetting()) {
            $field->setName($item->getSetting());
        }

        if ($item->getValue()) {
            $field->setValue($item->getValue());
        }

        $itemOptions = $item->getOptions();
        if (!is_null($itemOptions))
        {
            foreach ($item->getOptions() as $option => $value)
            {
                $setter = "set" . ucfirst($option);
                if (method_exists($field, $setter)) {
                    $field->$setter($value);
                }
            }
        }

        return $field;
    }
}