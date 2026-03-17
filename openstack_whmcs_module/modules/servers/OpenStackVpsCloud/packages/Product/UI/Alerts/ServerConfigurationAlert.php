<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\Product\UI\Alerts;

use ModulesGarden\OpenStackVpsCloud\Components\Alert\AlertInfo;

class ServerConfigurationAlert extends AlertInfo implements \ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface
{
    public function __construct()
    {
        parent::__construct();
        $this->setText($this->translate('finish_configuration'));
    }
}