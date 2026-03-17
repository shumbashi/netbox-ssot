<?php

namespace ModulesGarden\OpenStackVpsCloud\Components\FormInputEmail;

use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;

class FormInputEmail extends FormInputText
{
    public function __construct()
    {
        parent::__construct();

        $this->setType('email');
        $this->setPlaceholder('email@example.com');
    }
}
