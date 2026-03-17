<?php

use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\ClassExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\DirectoryExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\ExtensionExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\FileExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\FunctionExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\IsReadable;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\IsWritable;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\MethodExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\PhpMinVersion;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\WhmcsMinVersion;

return [
    new ExtensionExists('fileinfo'),
    new IsWritable(ModuleConstants::getFullPath('storage')),
    new IsReadable(ModuleConstants::getFullPath('storage')),
    new IsWritable(ModuleConstants::getFullPath('storage', 'gallery')),
    new IsReadable(ModuleConstants::getFullPath('storage', 'gallery')),
];

