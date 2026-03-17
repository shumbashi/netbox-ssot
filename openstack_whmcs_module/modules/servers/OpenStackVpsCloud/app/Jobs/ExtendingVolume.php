<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;

class ExtendingVolume extends JobsManager
{
    /**
     * @var array
     */
    protected $params;

    /**
     * @var VPSModel
     */
    protected $vm;

    /**
     * @var BlockDeviceModel
     */
    protected $currentBlock;

    /**
     * @param int $hid
     * @param int|null $pid
     * @param VPSModel|null $vm
     * @param BlockDeviceModel|null $currentBlock
     * @return bool|void
     * @throws \Exception
     */
    public function handle(int $hid = 1, int $pid = null, array $data = [])
    {
        $this->params = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);
        $this->vm = VPSModel::fromArray($data['vm']);
        $this->currentBlock = BlockDeviceModel::fromArray($data['currentBlock']);
        return $this->runTask($this->params, $pid);
    }

    /**
     * @return bool
     * @throws Exception
     * @throws OSException
     */
    public function runTaskAction()
    {
        $this->vm->setDetails();

        if (!$this->isAllowedExtending())
        {
            $this->log->info('Task postponed, awating block device detachment');
            return $this->postpone();
        }

        $this->currentBlock->extend($this->vm->getFlavor()->getDisk());

        $this->attacheVolume();

        $this->vm->checkStateTask();

        Api::getInstance()->compute()->startVPS($this->vm->getUUID());

        $this->vm->checkStateTask();

        Api::getInstance()->compute()->resize($this->vm->getUUID(), $this->vm->getFlavor()->getUUID());

        Queue::push(ConfirmingResizeVm::class,
            [
                'hid' => $this->params['serviceid'],
                'pid' => $this->params['pid'],
                'data' => [
                    'vm' => [
                        'UUID' => $this->vm->getUUID(),
                        'tenantID' => $this->vm->getTenantID(),
                    ],
                ]
            ],
            'default',
            null,
            'Hosting',
            $this->params['serviceid']);

        return true;
    }

    protected function isAllowedExtending()
    {
        foreach ($this->vm->getBlockDevices() as $block)
        {
            if ($block->getAttachDevice() == $this->currentBlock->getAttachDevice())
            {
                return false;
            }
        }

        return true;
    }

    protected function attacheVolume()
    {
        sleep(2);
        Api::getInstance()->compute()->volumeAttachment($this->vm->getUUID(), $this->currentBlock->getUUID(), $this->currentBlock->getAttachDevice());
        sleep(2);
    }
}