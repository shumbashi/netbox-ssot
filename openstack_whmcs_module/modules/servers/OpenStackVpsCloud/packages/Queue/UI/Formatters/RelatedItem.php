<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Formatters;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\RelatedItemLink;

class RelatedItem extends RelatedItemLink
{
    const DEFAULT_ALLOWED_TYPES = [
        'hosting',
        'addon',
        'domain',
        'client',
    ];

    protected static array $allowedTypes;

    public function __construct()
    {
        static::$allowedTypes = Config::get('queue.related_items_types', self::DEFAULT_ALLOWED_TYPES);
    }

    public function __invoke($fieldName, $row, $fieldValue, $raw)
    {
        if (!in_array(strtolower($raw->rel_type), static::$allowedTypes)) {
            return static::$defaultReturn;
        }

        return $this->format(strtolower($raw->rel_type), $raw->rel_id);
    }
}