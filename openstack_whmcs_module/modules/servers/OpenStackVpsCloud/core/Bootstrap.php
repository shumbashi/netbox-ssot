<?php

//boostrap
use ModulesGarden\OpenStackVpsCloud\Core\DependencyInjection\PackageServices;
use ModulesGarden\OpenStackVpsCloud\Core\DependencyInjection\Services;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'functions.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'Helper' . DIRECTORY_SEPARATOR . 'Functions.php';

/**
 * Initialize base values
 */
ModuleConstants::initialize();

/**
 * Initialize Services
 */
new Services();

/**
 * Boot packages
 */
\ModulesGarden\OpenStackVpsCloud\Core\make(PackageServices::class);