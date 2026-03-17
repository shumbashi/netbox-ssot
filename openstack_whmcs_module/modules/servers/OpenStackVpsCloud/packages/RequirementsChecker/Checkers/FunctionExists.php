<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers;

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;

class FunctionExists extends AbstractChecker
{
    protected string $functionName = '';

    public function __construct(string $functionName)
    {
        $this->functionName = $functionName;
    }

    public function check(): bool
    {
        return function_exists($this->functionName);
    }
}