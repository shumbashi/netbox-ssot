<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers;

use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\VersionComparator;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;

class WhmcsMinVersion extends AbstractChecker
{
    protected string $version = '';

    public function __construct(string $version)
    {
        $this->version = $version;
    }

    public function check(): bool
    {
        return VersionComparator::isWhmcsVersionHigherOrEqual($this->version);
    }
}