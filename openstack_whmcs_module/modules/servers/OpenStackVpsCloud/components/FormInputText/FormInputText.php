<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\FormInputText;

use ModulesGarden\OpenStackVpsCloud\Core\Components\FormFields\TextFormField;

class FormInputText extends TextFormField
{
    public const COMPONENT = 'FormInputText';

    public function setType(string $type): self
    {
        $this->setSlot('type', $type);

        return $this;
    }
}
