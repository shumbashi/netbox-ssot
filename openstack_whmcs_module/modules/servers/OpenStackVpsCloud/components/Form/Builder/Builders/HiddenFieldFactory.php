<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\Builders;

use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;

class HiddenFieldFactory extends AbstractFormFieldFactory
{
    public function create(FormFieldInterface $formField)//: FormFieldInterface
    {
        return $formField;
    }
}
