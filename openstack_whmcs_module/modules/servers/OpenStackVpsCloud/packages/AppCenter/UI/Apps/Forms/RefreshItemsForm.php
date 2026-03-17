<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Packages\AppCenter\UI\Apps\Providers\ItemRefreshProvider;

class RefreshItemsForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = ItemRefreshProvider::ACTION_REFRESH;
    protected string $provider = ItemRefreshProvider::class;

    public function loadHtml(): void
    {
        $this->builder->addField((new Dropdown())
            ->setName('action')
            ->setDefaultValueAsFirstOption());
    }
}