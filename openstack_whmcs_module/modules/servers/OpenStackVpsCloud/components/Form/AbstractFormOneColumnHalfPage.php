<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Form;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;

abstract class AbstractFormOneColumnHalfPage extends AbstractForm
{
    public function __construct()
    {
        parent::__construct();

        $this->builder = BuilderCreator::oneColumnHalfPage($this);
    }
}