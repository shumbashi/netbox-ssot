<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\DataTable\Modifiers;

use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\RelatedItem\RelatedItem;

class RelatedItemModifier extends RelatedItem
{
    public function __invoke($fieldName, $row, $fieldValue, $raw)
    {
        return self::formatFromData($row);
    }
}