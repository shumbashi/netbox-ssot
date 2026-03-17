<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\RadioButton;

use ModulesGarden\OpenStackVpsCloud\Core\Components\FormFields\FormField;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\OptionsTrait;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AvailableOptionsInterface;

class RadioButton extends FormField implements AvailableOptionsInterface
{
    use OptionsTrait;

    public const COMPONENT = 'RadioButton';
}
