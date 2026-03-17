<?php

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\IsDebugOff;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\IsCompiledOutputFileGenerated;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;

return [
    (new IsDebugOff())->setSeverity(AbstractChecker::SEVERITY_WARNING),
    (new IsCompiledOutputFileGenerated())->setSeverity(AbstractChecker::SEVERITY_WARNING),
];

