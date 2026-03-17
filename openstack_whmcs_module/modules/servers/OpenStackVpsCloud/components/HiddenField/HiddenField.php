<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\HiddenField;

use ModulesGarden\OpenStackVpsCloud\Core\Components\FormFields\FormField;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldHiddenInterface;

class HiddenField extends FormField implements FormFieldHiddenInterface
{
    public const COMPONENT = 'HiddenField';
}
