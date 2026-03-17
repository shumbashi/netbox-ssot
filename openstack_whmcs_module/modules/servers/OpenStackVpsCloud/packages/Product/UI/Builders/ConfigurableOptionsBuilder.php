<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Builders;

use ModulesGarden\OpenStackVpsCloud\Components\Form\AbstractForm;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\Builder;
use ModulesGarden\OpenStackVpsCloud\Components\FormGroup\FormGroup;
use ModulesGarden\OpenStackVpsCloud\Components\FormGroup\FormGroupFullWidth;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\GroupBuilders\ConfigOptionGroupBuilder;

class ConfigurableOptionsBuilder extends Builder
{
    public function __construct(AbstractForm $form)
    {
        parent:: __construct($form);
        $this->setDefaultFormGroup(new FormGroupFullWidth());
    }

    public function createGroup(FormFieldInterface $field, bool $showTooltip = true, FormGroup $formGroup = null)
    {
        return (new ConfigOptionGroupBuilder($this, $this->form, $this->defaultFormGroup))->build($field, $showTooltip, $formGroup);
    }
}