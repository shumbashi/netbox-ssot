<?php

namespace ModulesGarden\OpenStackVpsCloud\Fragments\ServerServicesTable\UI\Widgets;

use ModulesGarden\OpenStackVpsCloud\Components\DataTable\Column;
use ModulesGarden\OpenStackVpsCloud\Components\DataTable\DataTable;
use ModulesGarden\OpenStackVpsCloud\Components\Link\Link;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AdminAreaInterface;
use ModulesGarden\OpenStackVpsCloud\Core\Contracts\Components\AjaxComponentInterface;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\AbstractRecordsListDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\QueryDataProvider;
use ModulesGarden\OpenStackVpsCloud\Core\ModuleConstants;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Builders\ServiceStatusLabel;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\RelatedItem\RelatedItem;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Client;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Product;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Server;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\Service;
use ModulesGarden\OpenStackVpsCloud\Core\UI\Formatters\RelatedItem\ItemTypes;
use ModulesGarden\OpenStackVpsCloud\Core\DataProviders\Column as ColumnDataProviders;
use ModulesGarden\OpenStackVpsCloud\Fragments\ServerServicesTable\UI\Buttons\ServiceRedirectButton;
use ModulesGarden\OpenStackVpsCloud\Core\WHMCS\Models\ProductGroup;

class ServerServicesDataTable extends DataTable implements AdminAreaInterface, AjaxComponentInterface
{
    public function loadHtml(): void
    {
        $this->setTitle($this->translate('service_services_data_table'));

        $service = (new Service)->getTable();

        $this->addColumn((new Column('id', $service))
            ->setTitle($this->translate('id'))
            ->setSearchable(true, ColumnDataProviders::TYPE_INT)
            ->setSortable());

        $this->addColumn((new Column('domain', $service))
            ->setTitle($this->translate('domain'))
            ->setSearchable(true, ColumnDataProviders::TYPE_STRING)
            ->setSortable());

        $this->addColumn((new Column('client'))
            ->setTitle($this->translate('client')));

        $this->addColumn((new Column('domainstatus', $service))
            ->setTitle($this->translate('status'))
            ->setSearchable(true, ColumnDataProviders::TYPE_STRING)
            ->setSortable());

        $this->addColumn((new Column('product_id'))
            ->setTitle($this->translate('product')));

        $this->addActionButton(new ServiceRedirectButton());
    }


    public function loadData(): void
    {
        $service = (new Service)->getTable();
        $client = (new Client())->getTable();
        $product = (new Product())->getTable();
        $productGroup = (new ProductGroup())->getTable();

        $query = Service::query()
            ->whereIn('server', Server::where('type', ModuleConstants::getModuleName())->pluck('id'))
            ->join($client, "{$service}.userid", '=', "{$client}.id")
            ->join($product, "{$service}.packageid", '=', "{$product}.id")
            ->join($productGroup, "{$product}.gid", '=', "{$productGroup}.id")
            ->select([
                "{$service}.id",
                "{$service}.id as service_id",
                "{$service}.domain",
                "{$service}.domainstatus",
                "{$client}.id as client_id",
                "{$client}.firstname as client_firstname",
                "{$client}.lastname as client_lastname",
                "{$client}.companyname as company",
                "{$product}.id as product_id",
                "{$product}.name as product_name",
                "{$productGroup}.name as product_group_name",
            ])
            ->getQuery();

        $queryData = new QueryDataProvider($query);
        $queryData->setDefaultSorting('id', AbstractRecordsListDataProvider::SORT_DESC);

        $queryData->setColumns([
            (new ColumnDataProviders("{$service}.id", ColumnDataProviders::TYPE_INT, true, true)),
            (new ColumnDataProviders("{$service}.domain", ColumnDataProviders::TYPE_STRING, true, true)),
            (new ColumnDataProviders("{$service}.domainstatus", ColumnDataProviders::TYPE_STRING, true, true)),
            (new ColumnDataProviders("{$product}.name", ColumnDataProviders::TYPE_STRING, true, true)),
            (new ColumnDataProviders("{$client}.firstname", ColumnDataProviders::TYPE_STRING, true, true)),
            (new ColumnDataProviders("{$client}.lastname", ColumnDataProviders::TYPE_STRING, true, true)),
            (new ColumnDataProviders("{$productGroup}.name", ColumnDataProviders::TYPE_STRING, true, true)),
            (new ColumnDataProviders("{$client}.id", ColumnDataProviders::TYPE_INT, true, true))
        ]);

        $this->setDataProvider($queryData);
    }

    protected function parseDataSetRecords() : void
    {
        $this->dataSet->setFieldModifier('client', function($fieldName, $row, $fieldValue)
        {
            return RelatedItem::formatFromItem(new ItemTypes\Client($row['client_id']));
        });

        $this->dataSet->setFieldModifier('domain', function($fieldName, $row, $fieldValue)
        {
            if(!$fieldValue)
            {
                return "-";
            }

            return (new Link())
                ->setUrl((new ItemTypes\Service($row['id']))->generateUrl())
                ->setTitle($fieldValue);
        });

        $this->dataSet->setFieldModifier('id', function($fieldName, $row, $fieldValue)
        {
            return (new Link())
                ->setUrl((new ItemTypes\Service($fieldValue))->generateUrl())
                ->setTitle("#$fieldValue");
        });

        $this->dataSet->setFieldModifier('product_id', function($fieldName, $row, $fieldValue)
        {
            return (new Link())
                ->setUrl((new ItemTypes\Product($fieldValue))->generateUrl())
                ->setTitle(sprintf("%s - %s", $row['product_group_name'], $row['product_name']));
        });

        $this->dataSet->setFieldModifier('domainstatus', function($fieldName, $row, $fieldValue)
        {
            if (empty($fieldValue))
            {
                return "-";
            }

            return ServiceStatusLabel::create($fieldValue);
        });

        $this->dataSet->modifyRecords();
    }
}