<?php

namespace ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Dashboard\Pages;

use ModulesGarden\OpenStackVpsCloud\App\UI\Admin\Dashboard\Buttons\EditServer;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Core\Components\Actions\RedirectFromParam;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\ArrayDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\Helper\BuildUrl;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Server;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\Column as DataProviderColumn;

use WHMCS\Database\Capsule as DB;

class ServersDataTable extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate('servers'));
        $this->addColumn((new Column('id'))->setSearchable()->setSortable()->setTitle($this->translate('id')))
            ->addColumn((new Column('serverName'))->setSearchable()->setSortable()->setTitle($this->translate('name')))
            ->addColumn((new Column('hosting_count'))->setSortable()->setSearchable()->setTitle($this->translate('accounts_count')));
//            ->addColumn((new Column('serverType'))->setSearchable()->setSortable()->setTitle($this->translate('type')));

        $redirect = new EditServer();
        $redirect->setTitle($this->translate('edit_server'));
        $redirect->onClick((new RedirectFromParam('editUrl', ['id' => ':id'])));
        $this->addActionButton($redirect);
    }
    protected function parseDataSetRecords(): void
    {
        $this->dataSet->setFieldModifier('id', function($fieldName, &$row, $fieldValue) {
            $row['editUrl']          = BuildUrl::getBaseUrl() . 'configservers.php?action=manage';

            return $fieldValue;
        });

        $this->dataSet->setFieldModifier('hosting_count', function($fieldName, &$row, $fieldValue) {
            return $row['accounts_count_full'];
        });

        $this->dataSet->modifyRecords();
    }

    public function loadData(): void
    {
        $select = 'tblservers.id as id, tblservers.name serverName, tblservers.secure, tblservers.username, tblservers.ipaddress as ipaddress, tblservers.hostname as hostname, tblservers.password, tblservers.accesshash, tblservers.type as serverType, COUNT(tblhosting.id) hosting_count, CONCAT(COUNT(tblhosting.id),"/", tblservers.maxaccounts) as accounts_count_full';

        $data = Server::query()
            ->getQuery()
            ->select([DB::raw($select)])
            ->leftJoin('tblhosting', function($join) {
                $join->on('tblhosting.server', '=', 'tblservers.id')
                    ->where(function($q) {
                        $q->where('tblhosting.domainstatus', '=', 'Active')
                            ->orWhereNull('tblhosting.domainstatus');
                    });
            })
            ->whereIn('tblservers.type', [ModuleConstants::getModuleName()])
            ->groupBy('tblservers.id')
            ->get()
            ->toArray();

        foreach ($data as $key => &$value)
        {
            $value = (array) $value;
        }

        $dataProv = new ArrayDataProvider($data);
        $dataProv->setColumns([
            new DataProviderColumn('id', DataProviderColumn::TYPE_INT, true, true),
            new DataProviderColumn('serverName', DataProviderColumn::TYPE_STRING, true, true),
            new DataProviderColumn('hosting_count', DataProviderColumn::TYPE_STRING, true, true),
//            new DataProviderColumn('serverType', DataProviderColumn::TYPE_STRING, true, true),
            new DataProviderColumn('accounts_count_full', DataProviderColumn::TYPE_STRING, true),
        ]);

        $dataProv->setDefaultSorting('id', 'ASC');

        $this->setDataProvider($dataProv);
    }
}
