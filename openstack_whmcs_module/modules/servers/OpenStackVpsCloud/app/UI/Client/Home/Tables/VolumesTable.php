<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Tables;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Exceptions\OSException;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Models\BlockDeviceModel;
use ModulesGarden\OpenStackVpsCloud\App\Traits\ApiTrait;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxAutoReloadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxOnLoadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\ArrayDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin;

class VolumesTable extends DataTable implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface, AjaxAutoReloadInterface, AjaxOnLoadInterface
{
    use ApiTrait;
    public function loadHtml(): void
    {
        $this->search = false;

        $this->setTitle($this->translate("title"));

        $this->addColumn((new Column('name'))->setTitle($this->translate('name')))
            ->addColumn((new Column('size'))->setTitle($this->translate('size')))
            ->addColumn((new Column('status'))->setTitle($this->translate('status')))
            ->addColumn((new Column('type'))->setTitle($this->translate('type')))
            ->addColumn((new Column('attachedTo'))->setTitle($this->translate('attachedTo')))
            ->addColumn((new Column('isBootable'))->setTitle($this->translate('isBootable')));

        $this->loadData();
        $this->dataProvider->setSearch('');
        $this->dataSet = $this->dataProvider->getData();

        $this->setSlot('records', $this->dataSet->records);
    }

    public function loadData(): void
    {
        $volumes = $this->parseVolumes();

        $dataProvider = new ArrayDataProvider();
        $dataProvider->setData($volumes);
        $this->setDataProvider($dataProvider);
    }

    /**
     * @return array
     * @throws OSException
     */
    protected function parseVolumes()
    {
        $blockDevices = [];
        $devices = $this->api->compute()->getVolumeAttachments(Params::get('customfields.vmID'));
        foreach ($devices as $volume) {
            $tmp = new BlockDeviceModel(Params::get('customfields.vmID'), null, ['UUID' => $volume['volumeId']]);
            $tmp->setDetails();
            $blockDevices[] = $tmp;
        }

        foreach ($blockDevices as $volume) {
            $parseVolume = $volume->toArray();
            $parseVolume['size'] = $volume->getSize() . ' GB';
            $parseVolume['attachedTo'] = $volume->getAttachDevice() . ' on ' . $volume->getName();
            $parseVolume['isBootable'] = $volume->isBootable() ? 'Yes' : '-';

            $volumes[] = $parseVolume;
        }

        return $volumes;
    }

}