<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\Builders;

use ModulesGarden\OpenStackVpsCloud\Components\Container\ContainerFullWidth;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;

class ButtonFactory extends AbstractFormFieldFactory
{
    public function create(FormFieldInterface $formField)
    {
        $formGroup = new ContainerFullWidth();
        $formGroup->addElement($formField);

        return $formGroup;
    }
}
