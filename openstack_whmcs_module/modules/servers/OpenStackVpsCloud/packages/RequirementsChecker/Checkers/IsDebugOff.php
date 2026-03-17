<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers;

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;

class IsDebugOff extends AbstractChecker
{
    public function check(): bool
    {
        return !\ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('configuration.debug');
    }

}