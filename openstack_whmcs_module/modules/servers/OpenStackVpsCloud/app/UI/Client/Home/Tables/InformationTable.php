<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Tables;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Enums\InstanceInfo;
use ModulesGarden\OpenStackVpsCloud\App\Helpers\FeaturesHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\ProtectionVmManager;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelDanger;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelInfo;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelSuccess;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelWarning;
use ModulesGarden\OpenStackVpsCloud\Components\TableSimple\Record\Record;
use ModulesGarden\OpenStackVpsCloud\Components\TableSimple\TableSimpleAlignedColumns;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Controllers\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;

class InformationTable extends TableSimpleAlignedColumns implements ClientAreaInterface, AdminAreaInterface
{
    const ID = "informationTable";
    const VPS_STATUS_PENDING = 'pending';
    const VPS_STATUS_STOPPED = 'stopped';

    public function loadHtml(): void
    {
        $this->setId(self::ID);

        foreach ($this->getRows() as $row) {
            $this->addRecord((new Record($row)));
        }
    }

    protected function getRows()
    {
        $enabledFeatures = FeaturesHelper::getEnabledFeatures();

        $instanceData = $this->getAllInstanceData();

        if (!empty($enabledFeatures))
        {
            $instanceData = array_filter($instanceData, function ($k) use ($enabledFeatures) {
                return in_array($k, $enabledFeatures);
            }, ARRAY_FILTER_USE_KEY);
        }

        $rows = [];
        foreach ($instanceData as $key => $value) {
            $rows[] = [$this->translate($key), $value];
        }

        return $rows;
    }

    protected function getTenant()
    {
        return Factory::getTenantFromServiceId(Params::get('serviceid'));
    }

    protected function getAllInstanceData()
    {
        try {
            $instanceData = $this->getTenant()->VPS(Params::get('customfields.vmID'));
        } catch (\Exception $e) {
            return [];
        }

        return [
            InstanceInfo::INSTANCE_STATUS => $this->fillEmptyValue($this->getStatusLabel($instanceData->getStatus())),
            InstanceInfo::INSTANCE_NAME => $this->fillEmptyValue($instanceData->getName()),
            InstanceInfo::INSTANCE_IMAGE => $this->fillEmptyValue($instanceData->getImage()->getName()),
            InstanceInfo::INSTANCE_FLAVOR_NAME => $this->fillEmptyValue($instanceData->getFlavor()->getName()),
            InstanceInfo::INSTANCE_FLAVOR_DISK => $this->fillEmptyValue($instanceData->getFlavor()->getDisk(), 'GB'),
            InstanceInfo::INSTANCE_FLAVOR_RAM => $this->fillEmptyValue($instanceData->getFlavor()->getRam(), 'MB'),
            InstanceInfo::INSTANCE_FLAVOR_VCPUS => $this->fillEmptyValue($instanceData->getFlavor()->getVcpus()),
            InstanceInfo::INSTANCE_VOLUME_SIZE => $this->fillEmptyValue($this->getVolumeSize($instanceData), 'GB'),
            InstanceInfo::INSTANCE_REGION => $this->fillEmptyValue($this->getProductConfig()->getRegion()),
            InstanceInfo::INSTANCE_RESCUE => $this->fillEmptyValue($this->getProtectStatus()),
            InstanceInfo::INSTANCE_USER_DATA => '<div style="line-break: anywhere;">' . $this->fillEmptyValue($instanceData->getCustomScript()) . '</div>',
            InstanceInfo::INSTANCE_METADATA => '<div style="line-break: anywhere;">' . $this->fillEmptyValue($instanceData->getMetadata()) . '</div>',
        ];
    }

    public function fillEmptyValue($data = null, $unit = null)
    {
        if (!$data) {
            return '-';
        }

        if (is_array($data)) {
            $info = '';
            foreach ($data as $key => $value) {
                $info .= $key . ': ' . $value . ', ';
            }

            return rtrim($info, ', ');
        }

        if (is_string($data) && trim($data) === '') {
            return '';
        }

        if (!is_null($unit) && is_string($unit)) {
            return $data . ' ' . $unit;
        }

        return $data;
    }

    protected function getStatusLabel($status)
    {
        switch ($status) {
            case VPSModel::STATUS_ACTIVE:
                return (new LabelSuccess())->setText($this->translate(VPSModel::STATUS_ACTIVE))->displayAsStatusLabel();
            case self::VPS_STATUS_PENDING:
                return (new LabelWarning())->setText($this->translate(self::VPS_STATUS_PENDING))->displayAsStatusLabel();
            case self::VPS_STATUS_STOPPED:
                return (new LabelInfo())->setText($this->translate(self::VPS_STATUS_PENDING))->displayAsStatusLabel();
            default:
                return (new LabelDanger())->setText($this->translate($status))->displayAsStatusLabel();
        }
    }

    protected function getProductConfig()
    {
        return new ProductConfiguration(Params::get('serviceid'));
    }

    protected function getVolumeSize(VPSModel $instanceData)
    {
        $volumeSize = 0;
        foreach ($instanceData->getBlockDevices() as $device) {
            $volumeSize += $device->getSize();
        }

        return $volumeSize;
    }

    protected function getProtectStatus()
    {
        $protectManager = new ProtectionVmManager(Params::get('serviceid'));

        if ($protectManager->getStatus()) {
            return (new LabelSuccess())->setText($this->translate(ProtectionVmManager::STATUS_PROTECT))->displayAsStatusLabel();
        }

        return (new LabelWarning())->setText($this->translate(ProtectionVmManager::STATUS_NOT_PROTECTED))->displayAsStatusLabel();
    }
}