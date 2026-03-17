<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\FormInputGroupLabel;

use ModulesGarden\OpenStackVpsCloud\Core\Components\AbstractComponent;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;

/**
 * Class Form
 */
class FormInputGroupLabel extends AbstractComponent implements FormFieldInterface
{
    public const COMPONENT = 'FormInputGroupLabel';

    public function getName(): string
    {
        return '';
    }

    public function setText(string $text): self
    {
        $this->setSlot('text', $text);

        return $this;
    }
}
