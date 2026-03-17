<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Support;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Text;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\Enums\AppTypes;

class AppTypesTranslator
{
    use TranslatorTrait;

    public function getAvailableTranslated(): array
    {
        $translated = [];

        foreach (Config::get('appCenter.Apps') as $class) {
            $translated[$class] = $this->getTranslated($class);
        }
        return $translated;
    }

    public function getTranslated(string $type): string
    {
        $classPath = explode("\\", $type);
        $className = array_pop($classPath);
        return $this->translate(Text::toUnderscore($className));
    }

    public function getSingularTranslatedName(string $type): string
    {
        $classPath = explode("\\", $type);
        $className = array_pop($classPath);
        return $this->translate("singular_noun_" . Text::toUnderscore($className));
    }
}