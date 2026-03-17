<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Tables;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\v3\Api\Api;
use ModulesGarden\OpenStackVpsCloud\App\Traits\ApiTrait;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Home\Buttons\RemoveInterfaceButton;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxAutoReloadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\ArrayDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin;

class InterfacesTable extends DataTable implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface, AjaxAutoReloadInterface
{
    use ApiTrait;

    public function loadHtml(): void
    {
        $this->search = false;
        $this->hidePagination();
        $this->setTitle($this->translate("title"));

        $this->addColumn((new Column('fixedIP'))->setTitle($this->translate('fixedIP')));
        if (isAdmin()) {
            $this->addColumn((new Column('fixedNetwork'))->setTitle($this->translate('fixedNetwork')));
        }
        $this->addColumn((new Column('floatingIP'))->setTitle($this->translate('floatingIP')));
        if (isAdmin()) {
            $this->addColumn((new Column('floatingPool'))->setTitle($this->translate('floatingPool')));
        }
        $this->addColumn((new Column('mac'))->setTitle($this->translate('mac')));

        if (isAdmin()) {
            $this->addActionButton(new RemoveInterfaceButton());
        }

        $this->loadData();
        $this->dataProvider->setSearch('');
        $this->dataSet = $this->dataProvider->getData();

        $this->setSlot('records', $this->dataSet->records);
    }

    public function loadData(): void
    {
        $dataProvider = new ArrayDataProvider();
        $dataProvider->setData($this->getIntefaces());
        $this->setDataProvider($dataProvider);
    }

    protected function getIntefaces()
    {
        try {
            $entries = [];
            $networks = [];

            $interfaces = $this->api->compute()->listInterfaces(Params::get('customfields.vmID'));
            foreach ($interfaces['interfaceAttachments'] as $interface) {
                $port = $this->api->network()->getPort($interface['port_id']);

                /*Reduce amount of requests*/
                if (!isset($networks[$port['network_id']])) {
                    $fixedNetwork = Api::getInstance()->network()->getNetwork($port['network_id']);
                    $networks[$port['network_id']] = $fixedNetwork['network']['name'];
                }

                foreach ($port['fixed_ips'] as $ip) {
                    $entry = [
                        'mac' => $interface['mac_addr'],
                        'fixedIP' => $ip['ip_address'],
                        'fixedNetwork' => $networks[$port['network_id']],
                        'floatingIP' => '-',
                        'floatingPool' => '-'
                    ];

                    /*Prevent unnecessary exposure of information*/
                    if (isAdmin()) {
                        $entry['id'] = [
                            'port_id' => $port['id'],
                            'fixed_ip_address' => $ip['ip_address']
                        ];
                    }

                    $floatingIps = $this->api->network()->getFloatingIps([
                        'port_id' => $port['id'],
                        'fixed_ip_address' => $ip['ip_address']
                    ]);

                    if (empty($floatingIps)) {
                        $entries[] = $entry;
                        continue;
                    }

                    $floatingIp = reset($floatingIps);

                    /*Reduce amount of requests*/
                    if (!isset($networks[$floatingIp['floating_network_id']])) {
                        $floatingNetwork = $this->api->network()->getNetwork($floatingIp['floating_network_id']);
                        $networks[$floatingIp['floating_network_id']] = $floatingNetwork['network']['name'];
                    }

                    $entry['floatingIP'] = $floatingIp['floating_ip_address'];
                    $entry['floatingPool'] = $networks[$floatingIp['floating_network_id']];
                    $entries[] = $entry;
                }
            }

            if (isAdmin()) {
                /*Encode id for further actions*/
                foreach ($entries as &$entry) {
                    /*Base64 encode to prevent encoding html entities*/
                    $entry['id'] = base64_encode(json_encode($entry['id']));
                }
            }

        }
        catch (\Throwable $t) {
        }

        return $entries;
    }

    public function fillEmpty($data = null)
    {
        if (!$data) {
            return '-';
        }

        if (is_string($data) && trim($data) === '') {
            return '';
        }

        return $data;
    }
}