<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\Builders;

use ModulesGarden\OpenStackVpsCloud\Components\FormLabel\FormLabel;
use ModulesGarden\OpenStackVpsCloud\Components\Tooltip\Tooltip;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;

class DefaultFactory extends AbstractFormFieldFactory
{
    public function create(FormFieldInterface $formField)//: FormFieldInterface
    {
        $formField->appendCss('lu-form-control');

        $label = new FormLabel();
        $label->setCss('lu-form-label');
        $label->setText($this->title);

        if (!empty($this->description))
        {
            $icon = new Tooltip();
            $icon->setTitle($this->description);
            $label->addElement($icon);
        }

        $formGroup = $this->createContainer();
        $formGroup->addElement($label);
        $formGroup->addElement($formField);
        $formGroup->setFieldName($formField->getName());

        return $formGroup;
    }
}
