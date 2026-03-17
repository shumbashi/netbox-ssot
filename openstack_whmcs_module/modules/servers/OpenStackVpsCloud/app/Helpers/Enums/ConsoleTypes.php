<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums;

class ConsoleTypes
{
    const CONSOLE_VNC = 'VNC';
    const CONSOLE_RDP = 'RDP';
    const CONSOLE_SPICE = 'SPICE';
    const CONSOLE_SERIAL = 'Serial';
    const CONSOLE_TYPES = [self::CONSOLE_RDP, self::CONSOLE_SERIAL, self::CONSOLE_SPICE, self::CONSOLE_VNC];

}