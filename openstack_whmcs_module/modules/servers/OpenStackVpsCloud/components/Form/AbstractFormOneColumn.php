<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Form;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;

abstract class AbstractFormOneColumn extends AbstractForm
{
    public function __construct()
    {
        parent::__construct();

        $this->builder = BuilderCreator::oneColumn($this);
    }
}