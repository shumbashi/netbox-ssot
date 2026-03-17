<?php

use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\ClassExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\DirectoryExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\ExtensionExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\FileExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\FunctionExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\IsAddonModuleActive;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\IsReadable;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\IsWritable;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\MethodExists;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\PhpMinVersion;
use ModulesGarden\OpenStackVpsCloud\Packages\RequirementsChecker\Checkers\WhmcsMinVersion;

return [
    new PhpMinVersion('7.2.0'),
    new WhmcsMinVersion('7.9.0'),
    new MethodExists('SimpleXMLElement', 'attributes'),
    new DirectoryExists('/var/www'),
    new FileExists('/var/www/html/index.php'),
    new FunctionExists('exec'),
    new ClassExists('SimpleXMLElement'),
    new ExtensionExists('mysqli'),
    new IsWritable('/var/www'),
    new IsReadable('/var/www'),
    new IsAddonModuleActive("HostingRenewals")
];

