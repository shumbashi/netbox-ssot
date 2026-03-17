<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers;

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;

class DirectoryExists extends AbstractChecker
{
    protected string $path = '';

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function check(): bool
    {
        return is_dir($this->path);
    }
}