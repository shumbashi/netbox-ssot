<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts;

use function ModulesGarden\OpenStackVpsCloud\Core\translator;

class AbstractChecker implements CheckerInterface
{
    const SEVERITY_ERROR = "error";
    const SEVERITY_WARNING = "warning";

    protected string $severity = self::SEVERITY_ERROR;

    public function check(): bool
    {
        return false;
    }

    public function message(): string
    {
        return translator()->getBasedOnNamespaces(
            [get_class($this)],
            'message',
            get_object_vars($this)
        );
    }

    public function setSeverity(string $severity):self
    {
        $this->severity = $severity;

        return $this;
    }

    public function getSeverity():string
    {
        return $this->severity;
    }
}