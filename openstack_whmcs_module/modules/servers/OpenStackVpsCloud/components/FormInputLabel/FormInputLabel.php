<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\FormInputLabel;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\CssContainerTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\TextTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;

class FormInputLabel extends AbstractComponent implements FormFieldInterface
{
    use TextTrait;
    use CssContainerTrait;

    public const COMPONENT = 'FormInputLabel';

    public function getName(): string
    {
        return '';
    }

    public function setIcon(string $icon): self
    {
        $this->setSlot('icon', $icon);

        return $this;
    }

    public function setFor(string $for): self
    {
        $this->setSlot('for', $for);

        return $this;
    }
}