<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Pages;

use ModulesGarden\OpenStackVpsCloud\App\Libs\OpenstackVPS\FirewallConstants;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Buttons\AddFirewallRuleButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Buttons\DeleteFirewallRuleButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Buttons\MassDeleteFirewallRulesButton;
use ModulesGarden\OpenStackVpsCloud\App\UI\Client\Firewall\Providers\FirewallDataTableProvider;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxAutoReloadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxOnLoadInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\ClientAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\ArrayDataProvider;
use function ModulesGarden\OpenStackVpsCloud\Core\Helper\isAdmin;

class FirewallTable extends DataTable implements AdminAreaInterface, ClientAreaInterface, AjaxComponentInterface, AjaxAutoReloadInterface, AjaxOnLoadInterface
{
    const ID = 'firewallTable';

    public function loadHtml(): void
    {
        $this->setId(self::ID);

        $this->setTitle($this->translate("title"));

        $this->addColumn((new Column('direction'))->setSearchable()->setSortable()->setTitle($this->translate("direction")))
            ->addColumn((new Column('ethertype'))->setSearchable()->setSortable()->setTitle($this->translate("ethertype")))
            ->addColumn((new Column('protocol'))->setSearchable()->setSortable()->setTitle($this->translate("protocol")))
            ->addColumn((new Column('portRange'))->setSearchable()->setSortable()->setTitle($this->translate("portrange")))
            ->addColumn((new Column('remoteIPPrefix'))->setSearchable()->setSortable()->setTitle($this->translate("remoteipprefix")));

        $this->addToToolbar(new AddFirewallRuleButton());
        $this->addActionButton(new DeleteFirewallRuleButton());
        if (!isAdmin()) {
            $this->addMassActionButton(new MassDeleteFirewallRulesButton());
        }

        $this->loadData();
        $this->dataProvider->setSearch('');
        $this->dataSet = $this->dataProvider->getData();
    }

    public function loadData(): void
    {
        $dataProvider = (new FirewallDataTableProvider())->loadData();

        $arrayDataProvider = (new ArrayDataProvider())->setData($dataProvider->getData());

        $this->setDataProvider($arrayDataProvider);
    }

    protected function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('protocol', function($fieldName, $row, $fieldValue) {
            if (in_array($fieldValue, FirewallConstants::RULES)) {
                return strtoupper($fieldValue);
            }

            return $fieldValue;
        });


        $this->dataSet->modifyRecords();
    }
}