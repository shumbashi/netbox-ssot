<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Forms;

use ModulesGarden\OpenStackVpsCloud\App\UI\Actions\ConfigOptions\Providers\RefreshResourceProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;

class RefreshResourcesForm extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = RefreshResourceProvider::class;
    protected string $providerAction = RefreshResourceProvider::ACTION_REFRESH;
}