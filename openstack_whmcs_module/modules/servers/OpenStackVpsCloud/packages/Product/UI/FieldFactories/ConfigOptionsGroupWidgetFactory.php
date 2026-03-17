<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\FieldFactories;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\Builders\AbstractFormFieldFactory;
use ModulesGarden\OpenStackVpsCloud\Components\Row\Row;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;

class ConfigOptionsGroupWidgetFactory extends AbstractFormFieldFactory
{
    public function create(FormFieldInterface $formField)
    {
        $formGroup = $this->createContainer();

        $formGroup->appendCss('lu-form-check lu-p-0x lu-m-b-0x');
        $formGroup->addElement((new Row())->addElement($formField));

        return $formGroup;
    }
}