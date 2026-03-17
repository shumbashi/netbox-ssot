<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Forms;


use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Providers\FirewallProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\HiddenField\HiddenField;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Widget\Forms\FormConstants;

class MassDeleteFirewallRulesForm extends Form implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = FirewallProvider::class;
    protected string $providerAction = CrudProvider::ACTION_DELETE;

    public function loadHtml(): void
    {
        $id = (new HiddenField())
            ->setName('id');

        $this->builder->addField($id);
    }
}