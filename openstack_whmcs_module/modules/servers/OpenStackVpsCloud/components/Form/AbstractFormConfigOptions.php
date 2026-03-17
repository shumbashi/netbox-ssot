<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Form;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;

abstract class AbstractFormConfigOptions extends AbstractForm
{
    public function __construct()
    {
        parent::__construct();

        $this->builder = BuilderCreator::twoColumns($this);
        $this->setContainerTag('div');
    }
}