<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\FormSubmit;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Buttons\ServerConfiguration as ServerConfigButton;
use ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Providers\ServerConfigurationProxy as ServerConfigProxyProvider;

class ServerConfigurationProxy extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = ServerConfigProxyProvider::class;
    protected ?string $providerDefaultAction = null;

    public function loadHtml(): void
    {
        $button = new ServerConfigButton();
        $button->onClick((new FormSubmit($this))
            ->setCustomAction(CrudProvider::ACTION_CREATE)
            ->withSourceFormData('#frmServerConfig'));

        $this->builder->addButton($button);
    }
}