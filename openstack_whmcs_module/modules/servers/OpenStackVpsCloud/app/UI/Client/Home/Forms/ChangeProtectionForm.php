<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms;


use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers\ServiceActionsProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class ChangeProtectionForm extends Form implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface
{
    protected string $provider = ServiceActionsProvider::class;
    protected string $providerAction = ServiceActionsProvider::CHANGE_PROTECTION;
    protected ?string $providerDefaultAction = null;
}