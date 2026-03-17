<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms;


use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers\ControlPanelProvider;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers\ServiceActionsProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Widget\Forms\FormConstants;

class StopVMForm extends Form implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = ServiceActionsProvider::class;
    protected string $providerAction = ServiceActionsProvider::STOP;
    protected ?string $providerDefaultAction = null;
}