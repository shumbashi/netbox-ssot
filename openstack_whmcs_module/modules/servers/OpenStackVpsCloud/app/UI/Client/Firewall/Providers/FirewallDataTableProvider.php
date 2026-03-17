<?php


namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Providers;


use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\FirewallConstants;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\FirewallManager;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\AbstractRecordsListDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;

class FirewallDataTableProvider extends AbstractRecordsListDataProvider
{
    use TranslatorTrait;

    protected $response;
    /**
     * @var FirewallManager
     */
    protected $firewallManager;

    /**
     * @return $this
     */
    public function loadData()
    {
        $this->firewallManager = new FirewallManager(Params::all());

        $groupID = $this->firewallManager->getSecurityGroupID();
        $this->data = $this->firewallManager->getSecurityRules($groupID) ?? [];

        foreach ($this->data as &$record) {
            $record['direction'] = in_array($record['direction'], FirewallConstants::DIRECTIONS) ? $this->translate("firewall.protocol.${record['direction']}") : ucfirst($record['direction']);
//            $record['protocol']         = empty($record['protocol']) ?  $this->lang->T('firewall', 'protocol', 'any') : ucfirst($record['protocol']);
            $record['protocol'] = empty($record['protocol']) ? $this->translate('firewall.protocol.any') : ucfirst($record['protocol']);
            $record['portRange'] = $this->preparePort($record['port_range_min'], $record['port_range_max'], $record['protocol']);
            $record['remoteIPPrefix'] = (empty($record['remote_ip_prefix'])) ? '::/0' : $record['remote_ip_prefix'];
            $record['tags'] = implode(',', $record['tags']);
        }

        return $this;
    }

    private function assignPortToDefined($port)
    {
        $definedPorts = \ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config::get('portList', []);

        if (empty($definedPorts)) {
            return $port;
        }

        if (!empty($definedPorts->{$port})) {
            return $port . ' (' . $definedPorts->{$port} . ')';
        }

        return $port;
    }

    private function preparePort($min, $max, $protocol = null)
    {
        if ($protocol == "icmp") {
            if (empty($max) && !empty($min)) {
                $max = $this->translate('firewall.port_range_max.none');
            }
        }
        if (is_null($max)) {
            return $this->translate("firewall.protocol.any");
        }
        if (is_null($min) || $min == $max) {
            return $this->assignPortToDefined($max);
        }

        return $min . ' - ' . $max;
    }

    public function getData()
    {
        return $this->data;
    }
}