<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\FormGroup;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Decorator\Decorator;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\FormFieldInterface;

class FormGroupFullWidth extends FormGroup implements FormFieldInterface
{
    public function __construct()
    {
        parent::__construct();

        /*(new Decorator($this))->columns()->one();*/
    }
}
