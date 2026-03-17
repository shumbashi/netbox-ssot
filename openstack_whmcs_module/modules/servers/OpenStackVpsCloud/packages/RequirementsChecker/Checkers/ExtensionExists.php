<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers;

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;

class ExtensionExists extends AbstractChecker
{
    protected string $extension = '';

    public function __construct(string $extension)
    {
        $this->extension = $extension;
    }

    public function check(): bool
    {
        return extension_loaded($this->extension);
    }
}