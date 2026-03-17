<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers;

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;

class MethodExists extends AbstractChecker
{
    protected string $className = '';
    protected string $methodName = '';

    public function __construct(string $className, string $methodName)
    {
        $this->className  = $className;
        $this->methodName = $methodName;
    }

    public function check(): bool
    {
        return class_exists($this->className) && method_exists($this->className, $this->methodName);
    }
}