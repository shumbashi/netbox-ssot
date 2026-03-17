<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Config;

use ModulesGarden\OpenStackVpsCloud\Core\Data\Container;

class Config extends Container
{
    public function __construct()
    {
        global $CONFIG;
        parent::__construct($CONFIG);
    }
}