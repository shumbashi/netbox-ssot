<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Exceptions\PostponeTaskException;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\ConfirmingResizeVm;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\ExtendingVolume;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\Factory;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Tenant;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\FlavorModel;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;

/**
 * Class VmService
 * @package ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine
 */
class VmService
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var Tenant
     */
    protected $tenant;

    /**
     * @var VPSModel
     */
    protected $vm;

    /**
     * @var ProductConfiguration
     */
    protected $productConfig;

    /**
     * VmService constructor.
     * @param array $params
     * @throws OSException
     * @throws \OSException
     */
    public function __construct(array $params)
    {
        $this->params        = $params;
        $this->productConfig = new ProductConfiguration($params['serviceid']);
        $this->tenant        = Factory::getTenantAsUser($this->params, $this->productConfig->getTenantID());
        $this->loadVm();
    }

    /**
     * @throws OSException
     * @throws \OSException
     */
    protected function loadVm()
    {
        $vmID     = $this->params['customfields']['vmID'];
        $this->vm = $this->tenant->VPS($vmID);
    }

    /**
     * @return VPSModel
     */
    public function getVm()
    {
        return $this->vm;
    }

    /**
     * @param VPSModel $vm
     * @param string $oldFlavorId
     * @throws Exception
     * @throws OSException
     */
    public function changeFlavor(VPSModel $vm, string $oldFlavorId)
    {
        if (empty($vm->getUUID()))
        {
            throw new PostponeTaskException('ErrorManager: Unable to load VM. UUID is empty.');
        }

        if ($vm->getStateTask())
        {
            throw new PostponeTaskException('Task postponed, awaiting vm tasks completion.');
        }

        if (!$vm->getFlavor() instanceof FlavorModel)
        {
            throw new \Exception('Invalid flavor.');
        }

        if ($vm->getBlockDevices() && $this->isRunExtendVolume($vm))
        {
            return;
        }

        Api::getInstance()->compute()->resize($vm->getUUID(), $vm->getFlavor()->getUUID());

        Queue::push(ConfirmingResizeVm::class,
            [
                'hid'         => $this->params['serviceid'],
                'pid'         => $this->params['pid'],
                'data' => [
                    'vm' => [
                        'UUID' => $vm->getUUID(),
                        'tenantID' => $vm->getTenantID(),
                    ],
                    'oldFlavorId' => $oldFlavorId,
                    'newFlavorId' => $vm->getFlavor()->getUUID(),
                ]
            ],
            'default',
            null,
            'Hosting',
            $this->params['serviceid']);

    }

    protected function isRunExtendVolume(VPSModel $vm)
    {
        $currentBlock = $this->getCurrentBlock($vm->getBlockDevices());

        if ($currentBlock && ($vm->getFlavor()->getDisk() > $currentBlock->getSize()))
        {
            if (VPSModel::STATUS_SHUT_OFF !== $vm->getStatus())
            {
                Api::getInstance()->compute()->stopVPS($vm->getUUID());
                $vm->checkStateTask();
            }

            Api::getInstance()->compute()->volumeDeattachment($vm->getUUID(), $currentBlock->getUUID());

            Queue::push(ExtendingVolume::class, [
                    'hid' => $this->params['serviceid'],
                    'pid' => $this->params['pid'],
                    'vm'  => [
                        'tenantID' => $vm->getTenantID(),
                        'UUID' => $vm->getUUID(),
                        'details' => []
                    ],
                    'currentBlock' => $currentBlock
                ],
                'default',
                null,
                'Hosting',
                $this->params['serviceid']);

            return true;
        }

        return false;
    }

    /**
     * @param BlockDeviceModel[] $blockDevices
     * @return BlockDeviceModel|null
     */
    protected function getCurrentBlock(array $blockDevices)
    {
        foreach ($blockDevices as $device)
        {
            if ($device->getAttachDevice() == BlockDeviceModel::ATTACH_DEVICE_VDA)
            {
                return $device;
            }
        }

        return null;
    }

}
