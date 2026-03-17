<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Factories;

use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Libs\ReplacementFields\Source\CustomFieldsSectionInterface;

class CustomFieldsSectionFactory
{
    public static function factory(): array
    {
        $customRelatedSections = Config::get('appCenter.CustomRelatedFields', []);

        if (is_callable($customRelatedSections)) {
            $customRelatedSections = $customRelatedSections();
        }

        if (!is_array($customRelatedSections)) {
            throw new \Exception("Custom Related Fields config must be an array");
        }

        foreach ($customRelatedSections as $section) {
            if (!$section instanceof CustomFieldsSectionInterface) {
                throw new \Exception("Custom Section must implements CustomFieldsSectionInterface");
            }
        }

        return $customRelatedSections;
    }
}