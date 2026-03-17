<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers;

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;

/**
 * Check if provider class exists
 */
class ClassExists extends AbstractChecker
{
    protected string $className = '';

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function check(): bool
    {
        return class_exists($this->className);
    }
}