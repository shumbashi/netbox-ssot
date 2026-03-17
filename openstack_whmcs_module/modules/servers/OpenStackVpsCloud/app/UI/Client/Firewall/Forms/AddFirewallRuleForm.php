<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Forms;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\FirewallConstants;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Providers\FirewallProvider;
use ModulesGarden\OpenStackVpsCloud\Components\Dropdown\Dropdown;
use ModulesGarden\OpenStackVpsCloud\Components\Form\Form;
use ModulesGarden\OpenStackVpsCloud\Components\FormInputText\FormInputText;
use ModulesGarden\OpenStackVpsCloud\Components\Number\Number;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\Reload;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\CrudProvider;

class AddFirewallRuleForm extends Form implements ClientAreaInterface, AdminAreaInterface, AjaxComponentInterface
{
    protected string $provider = FirewallProvider::class;
    protected string $providerAction = CrudProvider::ACTION_CREATE;

    public function loadHtml(): void
    {
        $this->provider()->read();

        $selectedRule = $this->provider()->getValueById("rule") ?: FirewallConstants::RULE_TCP;

        $rule = (new Dropdown())
            ->setName('rule')
            ->setDefaultValue($selectedRule)
            ->required()
            ->setDescription($this->translate('ruleDescription'))
            ->onChange((new Reload($this)));

        $this->builder->addField($rule, true);

        $selectedDirection = $this->provider()->getValueById('direction') ?: FirewallConstants::DIRECTION_EGRESS;

        $direction = (new Dropdown())
            ->setName('direction')
            ->setDefaultValue($selectedDirection)
            ->required()
            ->setDescription($this->translate('directionDescription'));
        $this->builder->addField($direction, true);

        $this->initAppropriatedFields($selectedRule);

        $selectedIpRange = $this->provider()->getValueById('ipRange') ?: '';

        $ipRange = (new FormInputText())
            ->setDefaultValue($selectedIpRange)
            ->setName('ipRange')
            ->required()
            ->setDescription($this->translate('ipRangeDescription'));
        $this->builder->addField($ipRange, true);
    }

    public function initAppropriatedFields(string $selectedRule)
    {
        switch ($selectedRule) {
            case FirewallConstants::RULE_ICMP:
                $this->initIcmpFields();
                break;
            case FirewallConstants::RULE_CUSTOM:
                $this->initCustomFields();
                break;
            case FirewallConstants::RULE_TCP:
            case FirewallConstants::RULE_UPD:
            default:
                $this->initTcpOrUpdFields();
                break;
        }
    }

    private function initTcpOrUpdFields()
    {
        $selectedEtherType = $this->provider()->getValueById('ethertype') ?: FirewallConstants::IPV4;
        $etherType = (new Dropdown())
            ->setName('ethertype')
            ->setDescription($this->translate('etherTypeDescription'))
            ->setDefaultValue($selectedEtherType)
            ->required();
        $this->builder->addField($etherType, true);

        $selectedOpenPort = $this->provider()->getValueById('openPort') ?: FirewallConstants::OPEN_PORT_ALL_PORTS;

        $openPort = (new Dropdown())
            ->setName('openPort')
            ->setDescription($this->translate('openPortDescription'))
            ->setDefaultValue($selectedOpenPort)
            ->required()
            ->onChange((new Reload($this)));

        $this->builder->addField($openPort, true);

        $this->initFieldForOpenPort($selectedOpenPort);

    }

    private function initIcmpFields()
    {
        $selectedIcmpType = $this->provider()->getValueById('icmpType');

        $icmpType = (new Number())
            ->setName('icmpType')
            ->setDefaultValue($selectedIcmpType)
            ->setDescription($this->translate('icmpTypeDescription'))
            ->setRange(-1, 255)
            ->between(-1, 255)
            ->addValidator('numeric')
            ->required();
        $this->builder->addField($icmpType, true);

        $selectedIcmpCode = $this->provider()->getValueById('icmpCode') ?: -1;

        $icmpCode = (new Number())
            ->setName('icmpCode')
            ->setDefaultValue($selectedIcmpCode)
            ->setDescription($this->translate('icmpCodeDescription'))
            ->setRange(-1, 255)
            ->between(-1, 255)
            ->addValidator('numeric')
            ->required();

        $this->builder->addField($icmpCode, true);
    }

    private function initCustomFields()
    {
        $selectedIpProtocol = $this->provider()->getValueById('ipProtocol') ?: null;

        $ipProtocol = (new Number())
            ->setName('ipProtocol')
            ->setDefaultValue($selectedIpProtocol)
            ->setDescription($this->translate('ipProtocolDescription'))
            ->setRange(-1, 255)
            ->between(-1, 255)
            ->addValidator('numeric')
            ->required();

        $this->builder->addField($ipProtocol, true);
    }

    private function initFieldForOpenPort(string $selectedOpenPort)
    {
        switch ($selectedOpenPort) {
            case FirewallConstants::OPEN_PORT_PORT:

                $selectedPort = $this->provider()->getValueById('port') ?: FirewallConstants::CUSTOM_PORT;
                $port = (new Dropdown())
                    ->setName('port')
                    ->setDescription($this->translate('portDescription'))
                    ->setDefaultValue($selectedPort)
                    ->onChange((new Reload($this)))
                    ->required();

                $this->builder->addField($port, true);

                if ('customPort' != $selectedPort) {
                    break;
                }

                $selectedCustomPort = $this->provider()->getValueById('customPortField') ?: '';

                $customPortField = (new Number())
                    ->setName('customPortField')
                    ->setDefaultValue($selectedCustomPort)
                    ->setRange(1, 65535)
                    ->required()
                    ->addValidator('numeric')
                    ->between(1, 65535);

                $this->builder->addField($customPortField);

                break;

            case FirewallConstants::OPEN_PORT_PORT_RANGE:

                $selectedFromPort = $this->provider()->getValueById('fromPort') ?: null;

                $fromPort = (new Number())
                    ->setName('fromPort')
                    ->setDefaultValue($selectedFromPort)
                    ->setDescription($this->translate('fromPortDescription'))
                    ->setRange(1, 65535)
                    ->required()
                    ->addValidator('numeric')
                    ->between(1, 65535);

                $this->builder->addField($fromPort, true);

                $selectedToPort = $this->provider()->getValueById('toPort') ?: null;

                $toPort = (new Number())
                    ->setName('toPort')
                    ->setDefaultValue($selectedToPort)
                    ->setDescription($this->translate('toPortDescription'))
                    ->setRange(1, 65535)
                    ->required()
                    ->addValidator('numeric')
                    ->between(1, 65535);

                $this->builder->addField($toPort, true);

                break;

            case FirewallConstants::OPEN_PORT_ALL_PORTS:
            default:
                break;
        }
    }
}