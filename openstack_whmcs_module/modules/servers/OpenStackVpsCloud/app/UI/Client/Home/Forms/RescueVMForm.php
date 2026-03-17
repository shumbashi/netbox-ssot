<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers\RescueProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertDanger;
use ModulesGarden\OpenStackVpsCloud\Components\CopyToClipboardButton\CopyToClipboardButton;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputGroup\FormInputGroup;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class RescueVMForm extends Form implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = RescueProvider::ACTION_RESCUE;
    protected string $provider = RescueProvider::class;

    public function loadHtml(): void
    {
        $alert = new AlertDanger();
        $alert->setText($this->translate('rescueAlert'));

        $this->addElement($alert);

        $passwordGroup = new FormInputGroup();
        $passwordGroup->setName('passwordGroup');
        $passwordGroup->addElement((new FormInputText())
            ->setName('password')
            ->required()
            ->setReadOnly());

        $copyPassword = new CopyToClipboardButton();
        $copyPassword->copyFromUsingName('password');

        $passwordGroup->addElement($copyPassword);

        $this->builder->addField($passwordGroup);
    }
}