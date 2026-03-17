<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Helpers\Translators;

use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;

class ActionValidatorTranslator {

    use TranslatorTrait;

    const MESSAGE_CRITICAL_ACTIONS = 'critical_actions';

    public function getTranslated(string $lang)
    {
        return $this->translate($lang);
    }

    public function getCriticalActionsMessage()
    {
        return $this->translate(self::MESSAGE_CRITICAL_ACTIONS);
    }
}