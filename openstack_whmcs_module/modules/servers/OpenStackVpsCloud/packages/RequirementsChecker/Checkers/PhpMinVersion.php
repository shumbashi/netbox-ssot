<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers;

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;

class PhpMinVersion extends AbstractChecker
{
    protected string $version = '';

    public function __construct(string $version)
    {
        $this->version = $version;
    }

    public function check(): bool
    {
        return version_compare(phpversion(), $this->version, '>');
    }
}