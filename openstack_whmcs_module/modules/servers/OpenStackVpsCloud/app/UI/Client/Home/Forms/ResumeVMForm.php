<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Providers\ServiceActionsProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;

class ResumeVMForm extends Form implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    protected string $providerAction = ServiceActionsProvider::RESUME;
    protected string $provider = ServiceActionsProvider::class;
    protected ?string $providerDefaultAction = null;
}