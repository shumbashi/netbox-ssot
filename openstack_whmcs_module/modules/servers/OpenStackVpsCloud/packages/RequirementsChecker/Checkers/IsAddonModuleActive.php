<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers;

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;

class IsAddonModuleActive extends AbstractChecker
{
    protected string $addonName;
    protected string $friendlyModuleName;

    public function __construct(string $addonName, string $friendlyModuleName = "")
    {
        $this->addonName = $addonName;
        $this->friendlyModuleName = $friendlyModuleName ?: $addonName;
    }

    public function check(): bool
    {
        global $CONFIG;

        return in_array($this->addonName, explode(",", $CONFIG["ActiveAddonModules"]));
    }
}