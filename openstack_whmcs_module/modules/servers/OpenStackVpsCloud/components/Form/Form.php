<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\Form;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Builder\BuilderCreator;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Traits\ToolbarTrait;

class Form extends AbstractForm
{
    use ToolbarTrait;

    public function __construct()
    {
        parent::__construct();

        $this->builder = BuilderCreator::simple($this);
    }
}