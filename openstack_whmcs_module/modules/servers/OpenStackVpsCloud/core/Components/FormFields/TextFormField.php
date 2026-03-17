<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Components\FormFields;

class TextFormField extends FormField
{
    public function setValue($value): self
    {
        $this->setSlot('value', $value, true);

        return $this;
    }
}