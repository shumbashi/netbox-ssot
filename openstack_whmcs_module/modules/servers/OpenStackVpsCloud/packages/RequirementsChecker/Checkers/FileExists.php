<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers;

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;

class FileExists extends AbstractChecker
{
    protected string $path = '';

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function check(): bool
    {
        return is_file($this->path);
    }
}