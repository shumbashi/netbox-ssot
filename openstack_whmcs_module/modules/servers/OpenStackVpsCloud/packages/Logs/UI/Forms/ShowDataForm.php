<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Components\PreBlockArrayPrint\PreBlockArrayPrint;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\Logs\UI\Providers\ShowDataProvider;

class ShowDataForm extends Form implements AjaxComponentInterface, AdminAreaInterface
{
    protected string $provider = ShowDataProvider::class;
    protected string $providerAction = CrudProvider::ACTION_DELETE;

    public function loadHtml(): void
    {
        $this->builder->createField(HiddenField::class, 'id');
        $this->builder->createField(PreBlockArrayPrint::class, 'data');
    }
}
