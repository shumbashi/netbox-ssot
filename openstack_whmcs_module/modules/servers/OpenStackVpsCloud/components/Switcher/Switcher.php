<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Switcher;

use ModulesGarden\OpenStackVpsCloud\Core\Components\FormFields\FormField;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ComponentsContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\AjaxDataProviderTrait;

class Switcher extends FormField
{
    use ComponentsContainerTrait;
    use AjaxDataProviderTrait;

    //use only with setOnOffMode
    const STATE_ON = "on";
    const STATE_OFF = "off";

    public const COMPONENT = 'Switcher';

    public function setOnOffMode(): self
    {
        $this->setSlot('onOffMode', true);

        return $this;
    }
}
