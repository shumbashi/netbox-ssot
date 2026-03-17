<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Providers;

use ModulesGarden\OpenStackVpsCloud\App\Libs\Managers\ProductConfiguration;
use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\FirewallConstants;
use ModulesGarden\OpenStackVpsCloud\App\Libs\Services\VirtualMachine\Managers\FirewallManager;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Pages\FirewallTable;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Validators\PortRangeOrder;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ModalClose;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\ReloadById;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Response\Response;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Config;
use ModulesGarden\OpenStackVpsCloud\Core\Support\Facades\Params;
use ModulesGarden\OpenStackVpsCloud\Core\Translation\TranslatorTrait;

class FirewallProvider extends CrudProvider
{
    use TranslatorTrait;

    /**
     * @var ProductConfiguration
     */
    protected $productConfig;

    /**
     * FirewallProvider constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->productConfig = new ProductConfiguration(Params::get('serviceid'));
    }

    public function read()
    {
        parent::read();

        $rules = [];
        foreach (FirewallConstants::RULES as $rule) {
            $rules[$rule] = $this->translate("firewall.rule.$rule");
        }
        $this->availableValues['rule'] = $rules;


        $directions = [];
        foreach (FirewallConstants::DIRECTIONS as $direction) {
            $directions[$direction] = $this->translate("firewall.direction.$direction");
        }
        $this->availableValues['direction'] = $directions;

        $etherTypes = [];
        foreach (FirewallConstants::ETHER_TYPES as $type) {
            $etherTypes[$type] = $this->translate("firewall.etherType.$type");
        }
        $this->availableValues['ethertype'] = $etherTypes;

        $openPorts = [];
        foreach (FirewallConstants::OPEN_PORT_VARIABLE as $openPort) {
            $openPorts[$openPort] = $this->translate("firewall.openPort.$openPort");
        }
        $this->availableValues['openPort'] = $openPorts;

        $ports = [];
        foreach (Config::get('portList', []) as $port => $name) {
            $ports[$port] = sprintf('%s (%s)', $name, $port);
        }
        $ports['customPort'] = $this->translate("firewall.port.customPort");
        $this->availableValues['port'] = $ports;


    }

    public function create()
    {
        $firewallManager = new FirewallManager(Params::all());

        $postData = [
            FirewallConstants::PROTOCOL => $this->getProtocol($this->formData->toArray()),
            FirewallConstants::SECURITY_GROUP_ID => $firewallManager->getSecurityGroupID(),
            FirewallConstants::DIRECTION => $this->formData['direction'] == FirewallConstants::DIRECTION_INGRESS ? FirewallConstants::DIRECTION_INGRESS : FirewallConstants::DIRECTION_EGRESS,
            FirewallConstants::REMOTE_IP_PREFIX => $this->formData['ipRange']
        ];

        if ($this->formData['ethertype']) {
            $postData[FirewallConstants::ETHER_TYPE] = $this->formData['ethertype'];
        }

        if ($this->formData['rule'] == FirewallConstants::RULE_ICMP) {
            $postData['port_range_min'] = ($this->formData['icmpType'] == "-1") ? null : $this->formData['icmpType'];
            $postData['port_range_max'] = ($this->formData['icmpCode'] == "-1") ? null : $this->formData['icmpCode'];
            $postData['ethertype'] = FirewallConstants::IPV4;
        } else if (in_array($this->formData['rule'], FirewallConstants::RULES)) {
            switch ($this->formData['openPort']) {
                case FirewallConstants::OPEN_PORT_PORT_RANGE:
                    $this->validate($this->formData->toArray(), [
                        'fromPort' => [new PortRangeOrder($this->formData['toPort'])],
                    ]);

                    $postData['port_range_min'] = $this->formData['fromPort'];
                    $postData['port_range_max'] = $this->formData['toPort'];
                    break;
                case FirewallConstants::OPEN_PORT_PORT:
                    $postData['port_range_min'] = $this->formData['customPortField'];
                    $postData['port_range_max'] = $this->formData['customPortField'];
                    break;
            }
        }

        try {
            $firewallManager->addNewFirewallRule($postData);
        } catch (\Exception $exception) {
            return (new Response())->setError($exception->getMessage());
        }

        return (new Response())
            ->setSuccess($this->translate('firewallRuleCreatedSuccessfully'))
            ->setActions([
                new ModalClose(),
                new ReloadById(FirewallTable::ID)
            ]);
    }

    public function delete()
    {
        $rulesIDs = explode(',', $this->formData->get('id'));

        try {
            $firewallManager = new FirewallManager(Params::all());
            $firewallManager->deleteFirewallRules($rulesIDs);
        } catch (\Exception $exception) {
            return (new Response())->setError($exception->getMessage());
        }

        $message = count($rulesIDs) > 1 ? 'firewallRulesDeletedSuccessfully' : 'firewallRuleDeletedSuccessfully';
        return (new Response())
            ->setSuccess($this->translate($message))
            ->setActions([
                new ModalClose(),
                new ReloadById(FirewallTable::ID)
            ]);
    }

    private function getProtocol(array $formData)
    {
        if ($formData['rule'] === FirewallManager::RULE_CUSTOM) {
            return ($formData['ipProtocol'] == "-1") ? null : $formData['ipProtocol'];
        }

        return $formData['rule'];
    }
}