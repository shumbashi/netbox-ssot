<?php

namespace ModulesGarden\OpenStackVpsCloud\App\Jobs;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\WhmcsParamsHelper;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductCustomFields;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Models\VPSModel;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\FlavorModel;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Enums\Status;

class ConfirmingResizeVm extends JobsManager
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
     * @var string
     */
    protected $oldFlavorId;

    /**
     * @var string
     */
    protected $newFlavorId;

    /**
     * @param int $hid
     * @param int|null $pid
     * @param VPSModel|null $vm
     * @param string|null $oldFlavorId
     * @param string|null $newFlavorId
     * @return bool|void
     * @throws \Exception
     */
    public function handle(int $hid = 0, int $pid = null, array $data = [])
    {
        $this->params = WhmcsParamsHelper::getWhmcsParamsByHostingId($hid);

        $this->vm = VPSModel::fromArray($data['vm']);

        $this->oldFlavorId = $data['oldFlavorId'] ?: 0;
        $this->newFlavorId = $data['newFlavorId'] ?: 0;

        return $this->runTask($this->params, $pid);
    }

    /**
     * @return bool
     * @throws OSException
     * @throws \Exception
     */
    public function runTaskAction()
    {
        $this->vm->setDetails();

        if ($this->vm->getStateTask())
        {
            $this->log->info('Task postponed, awaiting task completion');
            return $this->postpone();
        }

        try {
            if (!in_array($this->vm->getStatus(), [VPSModel::STATUS_VERIFY_RESIZE, VPSModel::STATUS_ACTIVE, VPSModel::STATUS_SHUT_OFF] ))
            {
                $this->log->info('Task postponed, awaiting resize vm_status verify');
                return $this->postpone();
            }

            Api::getInstance()->compute()->confirmResize($this->vm->getUUID());

            if (!empty(trim($this->params['customfields']['privateFlavor']))) {
                $this->deleteUnnecessaryFlavor($this->oldFlavorId);
            }
        }
        catch (\Exception $exception){

            if (($this->vm->getStatus() === VPSModel::STATUS_ACTIVE || $this->vm->getStatus() === VPSModel::STATUS_SHUT_OFF)
                && preg_match('/cannot.+confirmResize.+vm_state (active|stopped)/i', $exception->getMessage())
            )
            {
                return true;
            }

            $this->model->setStatus(Status::WAITING);

            if (!empty(trim($this->params['customfields']['privateFlavor'])))
            {
                (new ProductCustomFields($this->params['pid'], $this->params['serviceid']))->updateFieldValue('privateFlavor', $this->oldFlavorId);

                $this->deleteUnnecessaryFlavor($this->newFlavorId);
            }

            if ($this->vm->getStatus() === VPSModel::STATUS_ACTIVE)
            {
                throw new \Exception('Flavor resize failed.');
            }

            throw $exception;
        }

        return true;
    }

    /**
     * Removal of old private flavor (if resize was successful)
     * or new private flavor (if resize failed)
     *
     * @param string $privateFlavorId
     * @throws \Exception
     */
    protected function deleteUnnecessaryFlavor(string $privateFlavorId): void
    {
        $flavorToDelete = new FlavorModel($this->vm->getTenantID(), $privateFlavorId);
        $flavorToDelete->delete();
    }
}