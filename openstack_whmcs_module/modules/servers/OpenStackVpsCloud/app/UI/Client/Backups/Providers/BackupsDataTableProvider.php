<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Backups\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters\BackupsFilter;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Filters\VolumeBackupsFilter;
use ModulesGarden\OpenStackVpsCloud\App\Models\Backups;
use ModulesGarden\OpenStackVpsCloud\App\Repositories\BackupsRepository;
use ModulesGarden\OpenStackVpsCloud\App\Traits\ApiTrait;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelDanger;
use ModulesGarden\OpenStackVpsCloud\Components\Label\LabelSuccess;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\AbstractRecordsListDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;

class BackupsDataTableProvider extends AbstractRecordsListDataProvider
{
    use TranslatorTrait;
    use ApiTrait;

    protected $response;

    /**
     * @var ProductConfiguration
     */
    protected $productConfig;

    public function loadData()
    {
        $this->productConfig = new ProductConfiguration(Params::get('serviceid'));

        $vmID = Params::get('customfields.vmID');
        $this->data = $this->getBackups();
        $backupTable = count($this->data) != 0 ? (new BackupsRepository())->getIDsAndPinnedBySource($vmID) : [];

        foreach ($this->data as $key => $backup) {
            if (array_search($backup['id'], array_column($backupTable, 'backupID')) === false) {
                Backups::create([
                    'sourceID' => $vmID,
                    'backupID' => $backup['id'],
                    'backupName' => $backup['name'],
                    'pinned' => false
                ]);
                $this->data[$key]['pinned'] = false;
            } else {
                $findArrayID = $this->searchForKeyByBackupID($backupTable, $backup['id']);
                $this->data[$key]['pinned'] = (bool)$backupTable[$findArrayID]['pinned'];
                $this->data[$key]['disabled_by_pinned'] = (bool)$backupTable[$findArrayID]['pinned'];
                $backupTable = $this->removeElementWithValue($backupTable, 'backupID', $backup['id']);
            }

            $this->data[$key]['backup_id'] = $backup['id'];
            $this->data[$key]['disabled_by_status'] = !in_array($this->data[$key]['status'], ['available', 'active']);
            $this->data[$key]['created'] = date('Y-m-d H:i:s', strtotime($backup['created']));
        }
        return $this;
    }

    private function getBackups(): array
    {
        if (!$this->productConfig->getUseVolumes()) {
            return (new BackupsFilter($this->api->image()->listBackups()))
                ->filterBySource(Params::get('customfields.vmID'))
                ->get();
        }

        return (new VolumeBackupsFilter($this->api->volume()->listVolumeBackups()))
            ->filterByServiceId(Params::get('serviceid'))
            ->get();
    }

    private function removeElementWithValue($array, $key, $value)
    {
        foreach ($array as $subKey => $subArray) {
            if ($subArray[$key] == $value) {
                unset($array[$subKey]);
            }
        }
        return $array;
    }

    private function searchForKeyByBackupID($array, $backupID)
    {
        foreach ($array as $key => $row) {
            if ($row['backupID'] == $backupID) {
                return $key;
            }
        }

        return null;
    }

    public function getData()
    {
        return $this->data;
    }
}