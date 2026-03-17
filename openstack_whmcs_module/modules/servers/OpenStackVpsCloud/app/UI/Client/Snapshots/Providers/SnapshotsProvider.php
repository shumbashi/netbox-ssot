<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Helpers\Translators\ActionValidatorTranslator;
use ModulesGarden\OpenStackVpsCloud\App\Jobs\RestoringVolumeProcess;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\JobManager;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters\SnapshotsFilter as SnapshotFilter;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;
use ModulesGarden\OpenStackVpsCloud\App\Traits\ApiTrait;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Snapshots\Pages\SnapshotsTable;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ReloadById;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;
use ModulesGarden\OpenStackVpsCloud\Packages\Queue\Support\Facades\Queue;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\di;

class SnapshotsProvider extends CrudProvider implements ClientAreaInterface
{
    use TranslatorTrait;
    use ApiTrait;

    const ACTION_RESTORE = 'restore';

    /**
     * @var ProductConfiguration
     */
    protected $productConfig;


    public function __construct()
    {
        parent::__construct();
        $this->productConfig = new ProductConfiguration(Params::get('serviceid'));
    }

    public function read()
    {
        $this->data['snapshotID'] = $this->actionElementId;
    }

    public function create()
    {
        try {
            if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
                return (new Response())
                    ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
            }

            $snapshots = (new SnapshotFilter($this->api->volume()->listSnapshots()))
                ->filterByServiceId(Params::get('serviceid'))
                ->get();

            if ($this->productConfig->getSnapshotsFilesLimit() != '-1' &&
                count($snapshots) >= (int)$this->productConfig->getSnapshotsFilesLimit()) {
                return (new Response())
                    ->setError($this->translate('limitReached'));
            }

            $device = $this->getBlockDevice();
            if (!$device) {
                return (new Response())
                    ->setError($this->translate('noDevice'));
            }

            $this->api->volume()->createSnapshot(
                $device['volumeId'],
                $this->formData['snapshotName'],
                [
                    SnapshotFilter::WHMCS_HOSTING_ID => strval(Params::get('serviceid')),
                ]
            );

            return (new Response())
                ->setSuccess($this->translate('snapshotCreation'))
                ->setActions([
                    (new ModalClose()),
                    (new ReloadById(SnapshotsTable::ID))
                ]);

        } catch (\Exception $ex) {
            return (new Response())->setError($ex->getMessage());
        }
    }

    public function delete()
    {
        if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
            return (new Response())
                ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
        }

        $snapshotIDs = explode(',', $this->formData['snapshotID']);

        $snapshots = (new SnapshotFilter($this->api->volume()->listSnapshots()))
            ->filterByServiceId(Params::get('serviceid'))
            ->filterIdsIn($snapshotIDs)
            ->get();

        foreach ($snapshots as $snapshot) {
            $this->api->volume()->deleteSnapshot($snapshot['id']);
        }

        return (new Response())
            ->setSuccess($this->translate('snapshotDelete'))
            ->setActions([
                (new ModalClose()),
                (new ReloadById(SnapshotsTable::ID))
            ]);
    }

    protected function getBlockDevice()
    {
        $devices = $this->api->compute()->getVolumeAttachments(Params::get('customfields.vmID'));
        return reset($devices);
    }

    public function restore()
    {
        if (JobManager::areCirticalBeingPerformed(Params::get('serviceid'))) {
            return (new Response())
                ->setError((new ActionValidatorTranslator())->getCriticalActionsMessage());
        }

        $snapshots = (new SnapshotFilter($this->api->volume()->listSnapshots()))
            ->filterByServiceId(Params::get('serviceid'))
            ->filterIdsIn([$this->formData['snapshotID']])
            ->get();

        if (empty($snapshots)) {
            return (new Response())
                ->setError($this->translate('invalidSnapshot'));
        }

        $device = $this->getBlockDevice();
        $details = $this->api->volume()->getVolume($device['volumeId']);

        $deviceModel = new BlockDeviceModel(Params::get('customfields.vmID'), null, ['UUID' => $device['volumeId']]);
        $deviceModel->setDetails($details);

        Queue::push(RestoringVolumeProcess::class,
            [
                'hid' => Params::get('serviceid'),
                'pid' => Params::get('pid'),
                'data' => [
                    'createFromSnapshot' => true,
                    'snapshotID' => $this->formData['snapshotID'],
                    'restoreBackupId' => $this->formData['snapshotID'],
                    'currentBlockDevice' => [
                        'size' => $deviceModel->getSize(),
                        'name' => $deviceModel->getName()
                    ],
                ]
            ],
            'default',
            null,
            'Hosting',
            Params::get('serviceid'));

        return (new Response())
            ->setSuccess($this->translate('snapshotRestoring'))
            ->setActions([
                (new ModalClose()),
            ]);
    }
}