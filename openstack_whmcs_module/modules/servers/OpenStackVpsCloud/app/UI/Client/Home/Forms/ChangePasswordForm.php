<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers\ServiceActionsProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertDanger;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputPassword\FormInputPassword;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class ChangePasswordForm extends Form implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    protected string $provider = ServiceActionsProvider::class;
    protected string $providerAction = ServiceActionsProvider::CHANGE_PASSWORD;
    protected ?string $providerDefaultAction = null;

    public function loadHtml(): void
    {
        $alert = new AlertDanger();
        $alert->setText($this->translate('confirmChangePassword'));
        $this->addElement($alert);

        $this->builder->createField(FormInputPassword::class, 'password');
    }
}