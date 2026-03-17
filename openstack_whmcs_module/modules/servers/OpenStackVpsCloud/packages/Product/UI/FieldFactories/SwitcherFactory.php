<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\FieldFactories;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\Builders\SwitcherFactory as BaseSwitcherFactory;
use ModulesGarden\OpenStackVpsCloud\Components\Span\Span;
use ModulesGarden\OpenStackVpsCloud\Components\Tooltip\Tooltip;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Formatters\ConfigOptionFullNameFormatter;

class SwitcherFactory extends BaseSwitcherFactory
{
    public function create(FormFieldInterface $formField)
    {
        $span = new Span();
        $span->setCss('lu-form-text');
        $span->addElement(ConfigOptionFullNameFormatter::buildFullNameContainer($this->title));

        if (!empty($this->description))
        {
            $icon = new Tooltip();
            $icon->setTitle($this->description);
            $span->addElement($icon);
        }

        $formField->appendCss('lu-form-control');
        $formField->addElement($span);

        $formGroup = $this->createContainer();

        $formGroup->appendCss('lu-form-check lu-m-b-2x');
        $formGroup->addElement($formField);

        return $formGroup;
    }
}