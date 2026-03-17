<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers\InterfacesTableProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;

class RemoveInterfaceForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = CrudProvider::ACTION_DELETE;
    protected string $provider = InterfacesTableProvider::class;

    public function loadHtml(): void
    {
        $id = (new HiddenField())
            ->setName('id');

        $this->builder->addField($id);
    }
}