<?php

namespace ModulesGarden\OpenStackVpsCloud\Core\Hook\Integration\Enums;

enum IntegrationTypes: string
{
    case After   = 'after';
    case Append  = 'append';
    case Before  = 'before';
    case Custom  = 'custom';
    case Prepend = 'prepend';
    case Replace = 'replace';
}