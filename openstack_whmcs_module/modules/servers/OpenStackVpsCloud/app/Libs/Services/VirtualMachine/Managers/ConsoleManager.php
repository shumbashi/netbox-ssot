<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;

class ConsoleManager extends BaseVpsManager
{
    const VNC    = 'VNC';
    const RDP    = 'RDP';
    const SPICE  = 'SPICE';
    const SERIAL = 'Serial';

    public function getConsoleUrl()
    {
        switch ($this->productConfig->getConsoleType())
        {
            case self::VNC:
                return Api::getInstance()->compute()->getVNCConsole($this->vm->getUUID());
            case self::RDP:
                return Api::getInstance()->compute()->getRDPConsole($this->vm->getUUID());
            case self::SPICE:
                return Api::getInstance()->compute()->getSpiceConsole($this->vm->getUUID());
            case self::SERIAL:
                return Api::getInstance()->compute()->getSerialConsole($this->vm->getUUID());
            default:
                throw new \Exception('Invalid console type');

        }
    }
}