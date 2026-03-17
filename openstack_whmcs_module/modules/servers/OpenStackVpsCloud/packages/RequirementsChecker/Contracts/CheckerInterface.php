<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts;

interface CheckerInterface
{
    public function check(): bool;

    public function message(): string;
}