<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers\DeleteItemConfigProvider;

class DeleteItemConfigForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = CrudProvider::ACTION_DELETE;
    protected string $provider = DeleteItemConfigProvider::class;

    public function loadHtml(): void
    {
        $this->builder->addField((new HiddenField())->setName('id'));
    }
}