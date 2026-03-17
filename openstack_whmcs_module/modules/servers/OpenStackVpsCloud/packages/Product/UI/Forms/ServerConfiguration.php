<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Forms;

use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;

class ServerConfiguration extends Form implements AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = \ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Providers\ServerConfiguration::class;
}