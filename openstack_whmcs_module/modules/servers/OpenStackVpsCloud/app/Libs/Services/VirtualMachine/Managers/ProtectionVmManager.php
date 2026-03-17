<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers;

use ModulesGarden\OpenStackVpsCloud\App\Models\Settings;

class ProtectionVmManager
{
    const PROTECT_VM           = 'protectVM';
    const IS_PROTECT           = 1;
    const STATUS_PROTECT       = 'protected';
    const STATUS_NOT_PROTECTED = 'not_protected';

    protected $serviceID;
    protected $settingsModel;

    /**
     * ProtectionVmManager constructor.
     * @param string $vmID
     * @param array $params
     */
    public function __construct(int $serviceId)
    {
        $this->serviceID     = $serviceId;
        $this->settingsModel = new Settings();
    }

    public function setTrue()
    {
        $this->settingsModel->setSetting($this->serviceID, self::PROTECT_VM, self::IS_PROTECT);
    }

    /**
     * Change vm protect status
     */
    public function change()
    {
        /**
         * Determine new protect status based on actual status
         */
        $actualStatus = $this->getStatus();
        $newStatus    = ($actualStatus == 0) ? 1 : 0;

        /**
         * Update status
         */
        $this->settingsModel->setSetting($this->serviceID, self::PROTECT_VM, $newStatus);
    }

    /**
     * @return int|null
     */
    public function getStatus()
    {
        $status = $this->settingsModel->byServiceID($this->serviceID)->bySetting(self::PROTECT_VM)->first();

        return $status->value;
    }
}