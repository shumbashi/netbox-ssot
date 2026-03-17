<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\UI\Providers\RunTaskProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;

class RunForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = RunTaskProvider::class;
    protected string $providerAction = RunTaskProvider::ACTION_RUN;

    public function loadHtml(): void
    {
        $this->builder->createField(HiddenField::class, 'id');
    }
}