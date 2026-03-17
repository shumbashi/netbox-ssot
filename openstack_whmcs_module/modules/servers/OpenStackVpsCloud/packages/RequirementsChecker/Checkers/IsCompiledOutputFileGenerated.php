<?php

namespace ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers;

use ModulesGarden\OpenStackVpsCloud\Core\Components\Compiler\CompilerOutputFileInfo;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Contracts\AbstractChecker;

class IsCompiledOutputFileGenerated extends AbstractChecker
{
    public function check(): bool
    {
        return !\ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('configuration.precompiledMode', false) ||
               !empty(file_get_contents(CompilerOutputFileInfo::getOutputFilePath()));
    }

}